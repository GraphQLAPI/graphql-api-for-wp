<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menus\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\CacheControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\EndpointOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\FieldDeprecationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Admin\BlockDevelopmentHotReload;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigurationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Taxonomies\GraphQLQueryTaxonomy;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLWithExplorerBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\PersistedQueryOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLEndpointPostType;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserRolesBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserStateBlock;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\EndpointBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlDisableAccessBlock;
use Leoloso\GraphQLByPoPWPPlugin\EndpointResolvers\AdminEndpointResolver;
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
use Leoloso\GraphQLByPoPWPPlugin\EditorScripts\EndpointEditorComponentScript;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigFieldDeprecationListBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLSchemaConfigurationPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\FieldDeprecationBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLFieldDeprecationListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\SchemaConfigurationBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\EditorScripts\PersistedQueryEditorComponentScript;

class Plugin
{
    protected $postTypeObjects = [];

    public function init(): void
    {
        /**
         * Menus
         */
        if (\is_admin()) {
            (new Menu())->init();
            (new BlockDevelopmentHotReload())->init();

            /**
             * Endpoint resolvers
             */
            (new AdminEndpointResolver())->init();
        }

        /**
         * Taxonomies (init them before Post Types)
         */
        (new GraphQLQueryTaxonomy())->init();

        /**
         * Post Types
         */
        $this->postTypeObjects[] = new GraphQLEndpointPostType();
        $this->postTypeObjects[] = new GraphQLPersistedQueryPostType();
        $this->postTypeObjects[] = new GraphQLSchemaConfigurationPostType();
        $this->postTypeObjects[] = new GraphQLAccessControlListPostType();
        $this->postTypeObjects[] = new GraphQLCacheControlListPostType();
        $this->postTypeObjects[] = new GraphQLFieldDeprecationListPostType();
        foreach ($this->postTypeObjects as $postTypeObject) {
            $postTypeObject->init();
        }

        /**
         * Editor Scripts
         */
        (new EndpointEditorComponentScript())->init();
        (new PersistedQueryEditorComponentScript())->init();

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

        /**
         * Plugin activation/deactivation
         */
        $this->handlePluginActivation();
    }

    /**
     * Handle all tasks required when activating/deactivating the plugin
     *
     * @return void
     */
    protected function handlePluginActivation(): void
    {
        /**
         * Get permalinks to work when activating the plugin
         * @see https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
         */
        \register_activation_hook(__FILE__, function () {
            // First, initialize all the custom post types. Their classes have already been instantiated
            $postTypeObjects = [];
            foreach ($postTypeObjects as $postTypeObject) {
                $postTypeObject->registerPostType();
            }
        
            // Then, flush rewrite rules
            \flush_rewrite_rules();
        });

        /**
         * Remove permalinks when deactivating the plugin
         * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
         */
        \register_deactivation_hook(__FILE__, function () {
            // First, unregister the post type, so the rules are no longer in memory.
            $postTypeObjects = [];
            foreach ($postTypeObjects as $postTypeObject) {
                $postTypeObject->unregisterPostType();
            }
        
            // Then, clear the permalinks to remove the post type's rules from the database.
            \flush_rewrite_rules();
        });
    }
}
