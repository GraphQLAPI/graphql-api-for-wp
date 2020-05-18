<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\CacheControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\EndpointOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\FieldDeprecationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigurationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLWithExplorerBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\PersistedQueryOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLEndpointPostType;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserRolesBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserStateBlock;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\EndpointBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlDisableAccessBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLPersistedQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigCacheControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\CacheControlBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserCapabilitiesBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigAccessControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLCacheControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AccessControlBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\PersistedQueryBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\QueryExecutionBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\EditorScripts\EndpointComponentEditorScript;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigFieldDeprecationListBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLSchemaConfigurationPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\FieldDeprecationBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLFieldDeprecationListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\SchemaConfigurationBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\EditorScripts\PersistedQueryComponentEditorScript;

class Plugin
{
    public function init(): void
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
        $schemaConfigurationBlock = new SchemaConfigurationBlock();
        $schemaConfigurationBlock->init();
        PluginState::setSchemaConfigurationBlock($schemaConfigurationBlock);

        // Maybe use GraphiQL with Explorer
        $graphiQLBlock = ComponentConfiguration::useGraphiQLWithExplorer() ?
            new GraphiQLWithExplorerBlock() :
            new GraphiQLBlock();
        $graphiQLBlock->init();
        PluginState::setGraphiQLBlock($graphiQLBlock);

        $accessControlBlock = new AccessControlBlock();
        $accessControlBlock->init();
        PluginState::setAccessControlBlock($accessControlBlock);

        $accessControlNestedBlock = new AccessControlDisableAccessBlock();
        $accessControlNestedBlock->init();
        PluginState::addAccessControlNestedBlock($accessControlNestedBlock);
        $accessControlNestedBlock = new AccessControlUserStateBlock();
        $accessControlNestedBlock->init();
        PluginState::addAccessControlNestedBlock($accessControlNestedBlock);
        $accessControlNestedBlock = new AccessControlUserRolesBlock();
        $accessControlNestedBlock->init();
        PluginState::addAccessControlNestedBlock($accessControlNestedBlock);
        $accessControlNestedBlock = new AccessControlUserCapabilitiesBlock();
        $accessControlNestedBlock->init();
        PluginState::addAccessControlNestedBlock($accessControlNestedBlock);

        $cacheControlBlock = new CacheControlBlock();
        $cacheControlBlock->init();
        PluginState::setCacheControlBlock($cacheControlBlock);

        $fieldDeprecationBlock = new FieldDeprecationBlock();
        $fieldDeprecationBlock->init();
        PluginState::setFieldDeprecationBlock($fieldDeprecationBlock);

        $schemaConfigAccessControlListBlock = new SchemaConfigAccessControlListBlock();
        $schemaConfigAccessControlListBlock->init();
        PluginState::setSchemaConfigAccessControlListBlock($schemaConfigAccessControlListBlock);

        $schemaConfigCacheControlListBlock = new SchemaConfigCacheControlListBlock();
        $schemaConfigCacheControlListBlock->init();
        PluginState::setSchemaConfigCacheControlListBlock($schemaConfigCacheControlListBlock);

        $schemaConfigFieldDeprecationListBlock = new SchemaConfigFieldDeprecationListBlock();
        $schemaConfigFieldDeprecationListBlock->init();
        PluginState::setSchemaConfigFieldDeprecationListBlock($schemaConfigFieldDeprecationListBlock);

        $schemaConfigOptionsBlock = new SchemaConfigOptionsBlock();
        $schemaConfigOptionsBlock->init();
        PluginState::setSchemaConfigOptionsBlock($schemaConfigOptionsBlock);

        $endpointOptionsBlock = new EndpointOptionsBlock();
        $endpointOptionsBlock->init();
        PluginState::setEndpointOptionsBlock($endpointOptionsBlock);

        $persistedQueryOptionsBlock = new PersistedQueryOptionsBlock();
        $persistedQueryOptionsBlock->init();
        PluginState::setPersistedQueryOptionsBlock($persistedQueryOptionsBlock);

        /**
         * Block categories
         */
        (new QueryExecutionBlockCategory())->init();
        (new EndpointBlockCategory())->init();
        (new PersistedQueryBlockCategory())->init();
        (new AccessControlBlockCategory())->init();
        (new CacheControlBlockCategory())->init();
        (new FieldDeprecationBlockCategory())->init();
        (new SchemaConfigurationBlockCategory())->init();
    }

    /**
     * Get permalinks to work when activating the plugin
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
     */
    public function activate(): void
    {
        // First, initialize all the custom post types
        $postTypeObjects = ContainerBuilderUtils::getServicesUnderNamespace(__NAMESPACE__ . '\\PostTypes');
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
        $postTypeObjects = ContainerBuilderUtils::getServicesUnderNamespace(__NAMESPACE__ . '\\PostTypes');
        foreach ($postTypeObjects as $postTypeObject) {
            $postTypeObject->unregisterPostType();
        }
    
        // Then, clear the permalinks to remove the post type's rules from the database.
        \flush_rewrite_rules();
    }
}
