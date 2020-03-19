<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\Admin\BlockDevelopmentHotReload;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlDisableAccessBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlUserStateBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLCacheControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AccessControlBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;

class Plugin {

    public function init(): void
    {
        /**
         * Configure the plugin
         */
        PluginConfiguration::init();

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

        /**
         * Blocks
         */
        $graphiQLBlock = new GraphiQLBlock();
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

        /**
         * Block categories
         */
        (new AccessControlBlockCategory())->init();

        /**
         * Clients
         */
        (new Clients())->init();
    }
}
