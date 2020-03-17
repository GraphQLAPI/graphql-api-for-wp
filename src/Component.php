<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\Root\Component\AbstractComponent;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractAccessControlListBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;
use PoP\Root\Component\YAMLServicesTrait;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;
    // const VERSION = '0.1.0';

    /**
     * Initialize services
     */
    public static function init()
    {
        parent::init();
        self::initYAMLServices(dirname(__DIR__));
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot()
    {
        parent::beforeBoot();

        // Initialize classes
        // Attach the Extensions with a higher priority, so it executes first
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__.'\\FieldResolvers\\Extensions', false, 100);
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        if (\is_singular(GraphQLQueryPostType::POST_TYPE)) {
            self::setAccessControlList();
        }
    }

    /**
     * Extract the access control items defined in the CPT, and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    protected static function setAccessControlList()
    {
        global $post;
        $graphQLQueryPost = $post;
        do {
            $aclPostID = \get_post_meta($graphQLQueryPost->ID, 'acl-post-id', true);
            // If it doesn't have an ACL defined, and it has a parent, check if it has an ACL, then use that one
            if (!$aclPostID && $graphQLQueryPost->post_parent) {
                $graphQLQueryPost = \get_post($graphQLQueryPost->post_parent);
            } else {
                // Make sure to exit the `while` for the root post, even if it doesn't have ACL
                $graphQLQueryPost = null;
            }
        } while (!$aclPostID && !is_null($graphQLQueryPost));

        // If we found an ACL, load its rules/restrictions
        if ($aclPostID) {
            $aclPost = \get_post($aclPostID);
            $blocks = \parse_blocks($aclPost->post_content);
            // Fetch all the content for all Access Control List blocks
            $aclBlocks = PluginState::getAccessControlListBlocks();
            $aclBlockFullNames = array_map(
                function($aclBlock) {
                    return $aclBlock->getBlockFullName();
                },
                $aclBlocks
            );
            $aclBlockItems = array_filter(
                $blocks,
                function($block) use($aclBlockFullNames) {
                    return in_array($block['blockName'], $aclBlockFullNames);
                }
            );
            $accessControlManager = AccessControlManagerFacade::getInstance();
            $instanceManager = InstanceManagerFacade::getInstance();
            $typeRegistry = TypeRegistryFacade::getInstance();
            $typeResolverClasses = $typeRegistry->getTypeResolverClasses();
            // For each class, obtain its namespacedTypeName
            $namespacedTypeNameClasses = [];
            foreach ($typeResolverClasses as $typeResolverClass) {
                $typeResolver = $instanceManager->getInstance($typeResolverClass);
                $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
                $namespacedTypeNameClasses[$typeResolverNamespacedName] = $typeResolverClass;
            }
            $directiveRegistry = DirectiveRegistryFacade::getInstance();
            $directiveResolverClasses = $directiveRegistry->getDirectiveResolverClasses();
            // For each class, obtain its directive name. Notice that different directives can have the same name (eg: @translate as implemented for Google and Azure),
            // then the mapping goes from name to list of resolvers
            $directiveNameClasses = [];
            foreach ($directiveResolverClasses as $directiveResolverClass) {
                $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
                $directiveResolverName = $directiveResolver->getDirectiveName();
                $directiveNameClasses[$directiveResolverName][] = $directiveResolverClass;
            }
            foreach ($aclBlockItems as $aclBlockItem) {
                if ($accessControlGroup = $aclBlockItem['attrs']['accessControlGroup']) {
                    // The value can be NULL
                    $value = $aclBlockItem['attrs']['value'];
                    // Extract the saved fields
                    $fields = [];
                    foreach (($aclBlockItem['attrs']['typeFields'] ?? []) as $selectedField) {
                        // The field is composed by the type namespaced name, and the field name, separated by "."
                        // Extract these values
                        $entry = explode(AbstractAccessControlListBlock::TYPE_FIELD_SEPARATOR, $selectedField);
                        $namespacedTypeName = $entry[0];
                        $field = $entry[1];
                        // From the type, obtain which resolver class processes it
                        if ($typeResolverClass = $namespacedTypeNameClasses[$namespacedTypeName]) {
                            // Check `getConfigurationEntries` to understand format of each entry
                            $fields[] = [$typeResolverClass, $field, $value];
                        }
                    }
                    if ($fields) {
                        $accessControlManager->addEntriesForFields(
                            $accessControlGroup,
                            $fields
                        );
                    }

                    // Extract the saved directives
                    $directives = [];
                    foreach (($aclBlockItem['attrs']['directives'] ?? []) as $selectedDirective) {
                        // Obtain the directive resolver class from the directive name. If more than one resolver has the same directive name, add all of them
                        if ($selectedDirectiveResolverClasses = $directiveNameClasses[$selectedDirective]) {
                            foreach ($selectedDirectiveResolverClasses as $directiveResolverClass) {
                                $directives[] = [$directiveResolverClass, $value];
                            }
                        }
                    }
                    if ($directives) {
                        $accessControlManager->addEntriesForDirectives(
                            $accessControlGroup,
                            $directives
                        );
                    }
                }
            }
        }
    }
}
