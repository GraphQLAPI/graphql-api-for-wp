<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\Front\Clients;

class Plugin {

    public function init(): void
    {
        // Initialize the GraphiQL block
        $graphiQLPath = 'vendor/leoloso/graphiql-wp-block';
        $graphiQLURLPath = \plugins_url($graphiQLPath, dirname(__FILE__));
        (new \Leoloso\GraphiQLWPBlock\Block($graphiQLURLPath))->init();

        // Menus
        if (is_admin()) {
            (new Menu())->init();
        }

        // Clients
        (new Clients())->init();
    }
}
