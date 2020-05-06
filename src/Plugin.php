<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\CacheControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\EndpointOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\FieldDeprecationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Admin\BlockDevelopmentHotReload;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigurationBlock;
use Leoloso\GraphQLByPoPWPPlugin\EndpointResolvers\AdminEndpointResolver;
use Leoloso\GraphQLByPoPWPPlugin\Taxonomies\GraphQLQueryTaxonomy;
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
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigFieldDeprecationListBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLSchemaConfigurationPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\FieldDeprecationBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLFieldDeprecationListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\SchemaConfigurationBlockCategory;

class Plugin
{
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
        (new GraphQLEndpointPostType())->init();
        (new GraphQLPersistedQueryPostType())->init();
        (new GraphQLSchemaConfigurationPostType())->init();
        (new GraphQLAccessControlListPostType())->init();
        (new GraphQLCacheControlListPostType())->init();
        (new GraphQLFieldDeprecationListPostType())->init();

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
         * Clients
         */
        (new Clients())->init();
    }
}
