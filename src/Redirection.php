<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\PoPAPIEndpointsForWP\EndpointUtils;

class Redirection {

    public $GRAPHIQL_ENDPOINT;
    public $VOYAGER_ENDPOINT;

    public function init()
    {
        $this->GRAPHIQL_ENDPOINT = apply_filters(
            __CLASS__.':endpoint',
            'graphiql'
        );
        $this->VOYAGER_ENDPOINT = apply_filters(
            __CLASS__.':endpoint',
            'interactive-schema'
        );

        add_action(
            'parse_request',
            [$this, 'parseRequest']
        );
    }

    public function parseRequest()
    {
        $redirectPaths = [
            trim($this->GRAPHIQL_ENDPOINT, '/') => 'graphiql',
            trim($this->VOYAGER_ENDPOINT, '/') => 'interactive-schema',
        ];
        $uri = EndpointUtils::getSlashedURI();
        if ($redirectPath = $redirectPaths[trim($uri, '/')]) {
            wp_redirect(GRAPHQL_BY_POP_PLUGIN_URL.$redirectPath);
            die;
        }
    }
}
