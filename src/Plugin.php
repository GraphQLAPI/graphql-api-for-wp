<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Redirection;

class Plugin {

    public function init(): void
    {
        if (is_admin()) {
            (new Menu())->init();
        }
        (new Redirection())->init();
    }
}
