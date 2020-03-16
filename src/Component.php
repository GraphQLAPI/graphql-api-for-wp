<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use PoP\Root\Component\AbstractComponent;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\Facades\Schema\TypeRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    // const VERSION = '0.1.0';

    /**
     * Boot component
     *
     * @return void
     */
    public static function earlyBoot()
    {
        parent::earlyBoot();

        if (\is_singular(GraphQLQueryPostType::POST_TYPE)) {
            self::maybeSetAccessControlList();
        }
    }

    protected static function maybeSetAccessControlList()
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
            $aclBlock = PluginState::getAccessControlListBlock();
            $aclBlockItems = array_filter(
                $blocks,
                function($block) use($aclBlock) {
                    return $block['blockName'] == $aclBlock->getBlockFullName();
                }
            );
            $accessControlManager = AccessControlManagerFacade::getInstance();
            $instanceManager = InstanceManagerFacade::getInstance();
            $typeRegistry = TypeRegistryFacade::getInstance();
            $typeResolverClasses = $typeRegistry->getTypeResolverClasses();
            // For each class, obtain its namespacedTypeName. Notice that if a TypeResolver overrides another, they may have the same name
            $namespacedTypeNameClasses = [];
            foreach ($typeResolverClasses as $typeResolverClass) {
                $typeResolver = $instanceManager->getInstance($typeResolverClass);
                $typeResolverNamespacedName = $typeResolver->getNamespacedTypeName();
                $namespacedTypeNameClasses[$typeResolverNamespacedName] = $typeResolverClass;
            }
            foreach ($aclBlockItems as $aclBlockItem) {
                if ($accessControlGroup = $aclBlockItem['attrs']['accessControlGroup']) {
                    $fields = [];
                    foreach ($aclBlockItem['attrs']['selectedFields'] as $selectedField) {
                        // The field is composed by the type namespaced name, and the field name, separated by "."
                        // Extract these values
                        $entry = explode('.', $selectedField);
                        $namespacedTypeName = $entry[0];
                        $field = $entry[1];
                        // From the type, obtain which resolver class processes it
                        if ($typeResolverClass = $namespacedTypeNameClasses[$namespacedTypeName]) {
                            // The entry is a tuple
                            $fields[] = [$typeResolverClass, $field];
                        }
                    }
                    if ($fields) {
                        $accessControlManager->addEntriesForFields(
                            $accessControlGroup,
                            $fields
                        );
                    }
                }
            }
        }
    }
}
