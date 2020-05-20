<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\ComponentModel\Container\ContainerBuilderUtils;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQL\GraphiQLBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLEndpointPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLPersistedQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQL\GraphiQLWithExplorerBlock;
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
        /**
         * Initialize classes for the admin panel
         */
        if (\is_admin()) {
            ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Admin\\Menus');
            ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Admin\\EndpointResolvers');
            ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Admin\\Development');
        }

        /**
         * Taxonomies must be initialized before Post Types
         */
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Taxonomies');
        /**
         * Initialize Post Types manually to control in what order they are added to the menu
         */
        ContainerBuilderUtils::instantiateService(GraphQLEndpointPostType::class);
        ContainerBuilderUtils::instantiateService(GraphQLPersistedQueryPostType::class);
        ContainerBuilderUtils::instantiateService(GraphQLSchemaConfigurationPostType::class);
        ContainerBuilderUtils::instantiateService(GraphQLAccessControlListPostType::class);
        ContainerBuilderUtils::instantiateService(GraphQLCacheControlListPostType::class);
        ContainerBuilderUtils::instantiateService(GraphQLFieldDeprecationListPostType::class);
        /**
         * Editor Scripts
         */
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\EditorScripts');
        /**
         * Blocks
         */
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Blocks', false);
        /**
         * Access Control Nested Blocks
         */
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Blocks\\AccessControlRuleBlocks', false);
        /**
         * Choose one of the GraphiQL Blocks
         */
        $graphiQLBlockClass =
            ComponentConfiguration::useGraphiQLWithExplorer() ?
            GraphiQLWithExplorerBlock::class : GraphiQLBlock::class;
        ContainerBuilderUtils::instantiateService($graphiQLBlockClass);
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
