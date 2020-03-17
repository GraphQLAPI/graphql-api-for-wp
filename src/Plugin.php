<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlDisableAccessBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\Admin\BlockDevelopmentHotReload;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLCacheControlListPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;

class Plugin {

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

        /**
         * Blocks
         */
        $graphiQLBlock = new GraphiQLBlock();
        $graphiQLBlock->init();
        PluginState::setGraphiQLBlock($graphiQLBlock);

        $accessControlListBlock = new AccessControlDisableAccessBlock();
        $accessControlListBlock->init();
        PluginState::addAccessControlListBlock($accessControlListBlock);

        /**
         * Clients
         */
        (new Clients())->init();
    }
}
