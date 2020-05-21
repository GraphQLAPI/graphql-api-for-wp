<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLEndpointPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLPersistedQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLCacheControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLSchemaConfigurationPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLFieldDeprecationListPostType;

class Plugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    public function initialize(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * Initialize classes for the admin panel
         */
        if (\is_admin()) {
            $menuServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\Menus');
            foreach ($menuServiceClasses as $serviceClass) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
            $endpointResolverServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\EndpointResolvers');
            foreach ($endpointResolverServiceClasses as $serviceClass) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
            $developmentServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\Development');
            foreach ($developmentServiceClasses as $serviceClass) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
        }

        /**
         * Taxonomies must be initialized before Post Types
         */
        $taxonomyServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Taxonomies');
        foreach ($taxonomyServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
        /**
         * Initialize Post Types manually to control in what order they are added to the menu
         */
        $postTypeServiceClasses = [
            GraphQLEndpointPostType::class,
            GraphQLPersistedQueryPostType::class,
            GraphQLSchemaConfigurationPostType::class,
            GraphQLAccessControlListPostType::class,
            GraphQLCacheControlListPostType::class,
            GraphQLFieldDeprecationListPostType::class,
        ];
        foreach ($postTypeServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
        /**
         * Editor Scripts
         */
        $editorScriptServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\EditorScripts');
        foreach ($editorScriptServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
        /**
         * Blocks
         * The GraphiQL Block may be overriden to GraphiQLWithExplorerBlock
         */
        $blockServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Blocks', false);
        foreach ($blockServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
        /**
         * Access Control Nested Blocks
         */
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Blocks\\AccessControlRuleBlocks', false);
        /**
         * Block categories
         */
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\BlockCategories');
    }

    /**
     * Get permalinks to work when activating the plugin
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
     */
    public function activate(): void
    {
        // First, initialize all the custom post types
        $instanceManager = InstanceManagerFacade::getInstance();
        $postTypeObjects = array_map(
            function ($serviceClass) use ($instanceManager) {
                return $instanceManager->getInstance($serviceClass);
            },
            ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\PostTypes')
        );
        foreach ($postTypeObjects as $postTypeObject) {
            $postTypeObject->registerPostType();
        }

        // Then, flush rewrite rules
        \flush_rewrite_rules();
    }

    /**
     * Remove permalinks when deactivating the plugin
     * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     */
    public function deactivate(): void
    {
        // First, unregister the post type, so the rules are no longer in memory.
        $instanceManager = InstanceManagerFacade::getInstance();
        $postTypeObjects = array_map(
            function ($serviceClass) use ($instanceManager) {
                return $instanceManager->getInstance($serviceClass);
            },
            ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\PostTypes')
        );
        foreach ($postTypeObjects as $postTypeObject) {
            $postTypeObject->unregisterPostType();
        }

        // Then, clear the permalinks to remove the post type's rules from the database.
        \flush_rewrite_rules();
    }
}
