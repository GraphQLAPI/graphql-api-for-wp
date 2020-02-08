<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Endpoints;
use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;

class Plugin {

    public function init(): void
    {
        (new EndpointHandler())->init();
        if (is_admin()) {
            (new Menu())->init();
        }
    }
}
