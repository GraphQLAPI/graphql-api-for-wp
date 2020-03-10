<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\Admin\BlockDevelopmentHotReload;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;

class Plugin {

    public function init(): void
    {
        // Menus
        if (\is_admin()) {
            (new Menu())->init();
            (new BlockDevelopmentHotReload())->init();
        }

        // Post Types
        (new GraphQLQueryPostType())->init();
        (new GraphQLAccessControlListPostType())->init();

        // Blocks
        $graphiQLBlock = new GraphiQLBlock();
        $graphiQLBlock->init();
        PluginState::setGraphiQLBlock($graphiQLBlock);

        // Clients
        (new Clients())->init();
    }
}
