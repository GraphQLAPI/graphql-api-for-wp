<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\GraphQLByPoPWPPlugin\Redirection;

class Plugin {

    public function init(): void
    {
        (new Redirection())->init();
    }
}
