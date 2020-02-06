<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Endpoints;

class Plugin {

    public function init(): void
    {
        Endpoints::init();
    }
}
