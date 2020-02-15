<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;

class Plugin {

    public function init(): void
    {
        if (is_admin()) {
            (new Menu())->init();
        }
        (new Clients())->init();
    }
}
