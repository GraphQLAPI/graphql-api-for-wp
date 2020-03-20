<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\Root\Component\AbstractComponent;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\CacheControl\Facades\CacheControlManagerFacade;
use PoP\ComponentModel\Facades\Registries\TypeRegistryFacade;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
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
            self::setCacheControlList();
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
            // Obtain the blocks of "Access Control" type
            $aclBlock = PluginState::getAccessControlBlock();
            $aclBlockFullName = $aclBlock->getBlockFullName();
            $aclBlockItems = array_filter(
                $blocks,
                function($block) use($aclBlockFullName) {
                    return $block['blockName'] == $aclBlockFullName;
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
            // The "Access Control" type contains the fields/directives
            foreach ($aclBlockItems as $aclBlockItem) {
                // The rule to apply is contained inside the nested blocks
                if ($aclBlockItemNestedBlocks = $aclBlockItem['innerBlocks']) {
                    $aclBlockItemTypeFields = $aclBlockItem['attrs']['typeFields'] ?? [];
                    $aclBlockItemDirectives = $aclBlockItem['attrs']['directives'] ?? [];
                    // Iterate all the nested blocks
                    foreach ($aclBlockItemNestedBlocks as $aclBlockItemNestedBlock) {
                        if ($accessControlGroup = $aclBlockItemNestedBlock['attrs']['accessControlGroup']) {
                            // The value can be NULL, it depends on the actual nestedBlock (eg: Disable access doesn't have any, while Disable by role has the list of roles)
                            $value = $aclBlockItemNestedBlock['attrs']['value'];
                            // Extract the saved fields
                            $entriesForFields = [];
                            foreach ($aclBlockItemTypeFields as $selectedField) {
                                // The field is composed by the type namespaced name, and the field name, separated by "."
                                // Extract these values
                                $entry = explode(AccessControlBlock::TYPE_FIELD_SEPARATOR, $selectedField);
                                $namespacedTypeName = $entry[0];
                                $field = $entry[1];
                                // From the type, obtain which resolver class processes it
                                if ($typeResolverClass = $namespacedTypeNameClasses[$namespacedTypeName]) {
                                    // Check `getConfigurationEntries` to understand format of each entry
                                    $entriesForFields[] = [$typeResolverClass, $field, $value];
                                }
                            }
                            if ($entriesForFields) {
                                $accessControlManager->addEntriesForFields(
                                    $accessControlGroup,
                                    $entriesForFields
                                );
                            }

                            // Extract the saved directives
                            $entriesForDirectives = [];
                            foreach ($aclBlockItemDirectives as $selectedDirective) {
                                // Obtain the directive resolver class from the directive name. If more than one resolver has the same directive name, add all of them
                                if ($selectedDirectiveResolverClasses = $directiveNameClasses[$selectedDirective]) {
                                    foreach ($selectedDirectiveResolverClasses as $directiveResolverClass) {
                                        $entriesForDirectives[] = [$directiveResolverClass, $value];
                                    }
                                }
                            }
                            if ($entriesForDirectives) {
                                $accessControlManager->addEntriesForDirectives(
                                    $accessControlGroup,
                                    $entriesForDirectives
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Extract the cache control items defined in the CPT, and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    protected static function setCacheControlList()
    {
        global $post;
        $graphQLQueryPost = $post;
        do {
            $cclPostID = \get_post_meta($graphQLQueryPost->ID, 'ccl-post-id', true);
            // If it doesn't have a CCL defined, and it has a parent, check if it has an ACL, then use that one
            if (!$cclPostID && $graphQLQueryPost->post_parent) {
                $graphQLQueryPost = \get_post($graphQLQueryPost->post_parent);
            } else {
                // Make sure to exit the `while` for the root post, even if it doesn't have ACL
                $graphQLQueryPost = null;
            }
        } while (!$cclPostID && !is_null($graphQLQueryPost));

        // If we found an ACL, load its rules/restrictions
        if ($cclPostID) {
            $cclPost = \get_post($cclPostID);
            $blocks = \parse_blocks($cclPost->post_content);
            // Obtain the blocks of "Access Control" type
            $cclBlock = PluginState::getCacheControlBlock();
            $cclBlockFullName = $cclBlock->getBlockFullName();
            $cclBlockItems = array_filter(
                $blocks,
                function($block) use($cclBlockFullName) {
                    return $block['blockName'] == $cclBlockFullName;
                }
            );
            $cacheControlManager = CacheControlManagerFacade::getInstance();
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
            // The "Cache Control" type contains the fields/directives and the max-age
            foreach ($cclBlockItems as $cclBlockItem) {
                if ($maxAge = $cclBlockItem['attrs']['cacheControlMaxAge']) {
                    // Convert from string to integer
                    $value = (int)$maxAge;
                    $typeFields = $cclBlockItem['attrs']['typeFields'] ?? [];
                    $directives = $cclBlockItem['attrs']['directives'] ?? [];
                    // Extract the saved fields
                    $entriesForFields = [];
                    foreach ($typeFields as $selectedField) {
                        // The field is composed by the type namespaced name, and the field name, separated by "."
                        // Extract these values
                        $entry = explode(AccessControlBlock::TYPE_FIELD_SEPARATOR, $selectedField);
                        $namespacedTypeName = $entry[0];
                        $field = $entry[1];
                        // From the type, obtain which resolver class processes it
                        if ($typeResolverClass = $namespacedTypeNameClasses[$namespacedTypeName]) {
                            // Check `getConfigurationEntries` to understand format of each entry
                            $entriesForFields[] = [$typeResolverClass, $field, $value];
                        }
                    }
                    if ($entriesForFields) {
                        $cacheControlManager->addEntriesForFields(
                            $entriesForFields
                        );
                    }

                    // Extract the saved directives
                    $entriesForDirectives = [];
                    foreach ($directives as $selectedDirective) {
                        // Obtain the directive resolver class from the directive name. If more than one resolver has the same directive name, add all of them
                        if ($selectedDirectiveResolverClasses = $directiveNameClasses[$selectedDirective]) {
                            foreach ($selectedDirectiveResolverClasses as $directiveResolverClass) {
                                $entriesForDirectives[] = [$directiveResolverClass, $value];
                            }
                        }
                    }
                    if ($entriesForDirectives) {
                        $cacheControlManager->addEntriesForDirectives(
                            $entriesForDirectives
                        );
                    }
				}
            }
        }
    }
}
