<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;

class Plugin {

    public function init(): void
    {
        // Menus
        if (\is_admin()) {
            (new Menu())->init();
        }

        // Blocks
        (new GraphiQLBlock())->init();

        // Clients
        (new Clients())->init();
    }
}
