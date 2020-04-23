<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigurationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLWithExplorerBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\CacheControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\Admin\BlockDevelopmentHotReload;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserRolesBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserStateBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlDisableAccessBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigCacheControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\CacheControlBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserCapabilitiesBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigAccessControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLCacheControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AccessControlBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\PersistedQueryBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLSchemaConfigurationPostType;
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
        }

        /**
         * Post Types
         */
        (new GraphQLQueryPostType())->init();
        (new GraphQLAccessControlListPostType())->init();
        (new GraphQLCacheControlListPostType())->init();
        (new GraphQLSchemaConfigurationPostType())->init();

        /**
         * Blocks
         */
        $schemaConfigurationBlock = new SchemaConfigurationBlock();
        $schemaConfigurationBlock->init();
        PluginState::setSchemaConfigurationBlock($schemaConfigurationBlock);

        $graphiQLBlock = true ? new GraphiQLWithExplorerBlock() : new GraphiQLBlock();
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

        $schemaConfigAccessControlListBlock = new SchemaConfigAccessControlListBlock();
        $schemaConfigAccessControlListBlock->init();
        PluginState::setSchemaConfigAccessControlListBlock($schemaConfigAccessControlListBlock);

        $schemaConfigCacheControlListBlock = new SchemaConfigCacheControlListBlock();
        $schemaConfigCacheControlListBlock->init();
        PluginState::setSchemaConfigCacheControlListBlock($schemaConfigCacheControlListBlock);

        /**
         * Block categories
         */
        (new PersistedQueryBlockCategory())->init();
        (new AccessControlBlockCategory())->init();
        (new CacheControlBlockCategory())->init();
        (new SchemaConfigurationBlockCategory())->init();

        /**
         * Clients
         */
        (new Clients())->init();
    }
}
