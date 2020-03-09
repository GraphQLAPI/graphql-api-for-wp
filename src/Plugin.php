<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Admin\BlockDevelopmentHotReload;

class Plugin {

    public function init(): void
    {
        // Menus
        if (\is_admin()) {
            (new Menu())->init();
            (new BlockDevelopmentHotReload())->init();
        }

        // Blocks
        (new GraphiQLBlock())->init();

        // Clients
        (new Clients())->init();
    }
}
