<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Front;

use Leoloso\PoPAPIEndpointsForWP\EndpointUtils;
use Leoloso\GraphQLByPoPWPPlugin\General\EndpointHelpers;

class Clients {

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

    // public function parseRequest()
    // {
    //     $redirectPaths = [
    //         trim($this->GRAPHIQL_ENDPOINT, '/') => 'graphiql',
    //         trim($this->VOYAGER_ENDPOINT, '/') => 'interactive-schema',
    //     ];
    //     $uri = EndpointUtils::getSlashedURI();
    //     if ($redirectPath = $redirectPaths[trim($uri, '/')]) {
    //         wp_redirect(GRAPHQL_BY_POP_PLUGIN_URL.$redirectPath);
    //         die;
    //     }
    // }
    public function parseRequest()
    {
        $uri = EndpointUtils::getSlashedURI();
        $uri = trim($uri, '/');
        $graphiQLTrimmedEndpoint = trim($this->GRAPHIQL_ENDPOINT, '/');
        $voyagerTrimmedEndpoint = trim($this->VOYAGER_ENDPOINT, '/');
        $dirPaths = [
            $graphiQLTrimmedEndpoint => 'vendor/leoloso/pop-graphiql',
            $voyagerTrimmedEndpoint => 'vendor/leoloso/pop-graphql-voyager',
        ];
        if ($dirPath = $dirPaths[$uri]) {
            $htmlFileNames = [
                $graphiQLTrimmedEndpoint => 'index.html',
                $voyagerTrimmedEndpoint => 'index.html',
            ];
            // Read the file, and return it already
            $file = GRAPHQL_BY_POP_PLUGIN_DIR.$dirPath.'/'.$htmlFileNames[$uri];
            $fileContents = file_get_contents($file, true);
            // Modify the script path
            $jsFileNames = [
                $graphiQLTrimmedEndpoint => 'graphiql.js',
                $voyagerTrimmedEndpoint => 'voyager.js',
            ];
            if ($jsFileName = $jsFileNames[$uri]) {
                $jsFileURL = GRAPHQL_BY_POP_PLUGIN_URL.$dirPath.'/'.$jsFileName;
                $fileContents = str_replace($jsFileName.'?', $jsFileURL.'?endpoint='.EndpointHelpers::getGraphQLEndpoint(true).'&', $fileContents);
            }
            echo $fileContents;
            die;
        }
    }
}
