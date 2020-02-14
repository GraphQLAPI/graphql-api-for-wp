<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;

class Plugin {

    public function init(): void
    {
        if (is_admin()) {
            (new Menu())->init();
        }
    }
}
