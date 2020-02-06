<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;

class Redirection {

    /**
     * GraphQL endpoint
     *
     * @var string
     */
    public static $GRAPHQL_ENDPOINT;
    /**
     * REST endpoint
     *
     * @var string
     */
    public static $REST_ENDPOINT;
    /**
     * Native API endpoint
     *
     * @var string
     */
    public static $API_ENDPOINT;

    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public static function init(): void
    {
        /**
         * Define the endpoints
         */
        self::$GRAPHQL_ENDPOINT = apply_filters(
            'graphql_by_pop:graphql_endpoint',
            'api/graphql'
        );
        self::$REST_ENDPOINT = apply_filters(
            'graphql_by_pop:rest_endpoint',
            'api/rest'
        );
        self::$API_ENDPOINT = apply_filters(
            'graphql_by_pop:api_endpoint',
            'api'
        );

        /**
         * Register the endpoints
         */
        add_action(
            'init',
            [self::class, 'addRewriteEndpoints']
        );
        add_filter(
            'query_vars',
            [self::class, 'addQueryVar'],
            10,
            1
        );

        /**
         * Process the request to find out if it is any of the endpoints
         */
        add_action(
            'parse_request',
            [self::class, 'parseRequest']
        );
    }

    /**
     * Indicate if the URI ends with the given endpoint
     *
     * @param string $uri
     * @param string $endpointURI
     * @return boolean
     */
    private static function endsWithString(string $uri, string $endpointURI): bool
    {
        $endpointSuffix = '/'.trim($endpointURI, '/').'/';
        return substr($uri, -1*strlen($endpointSuffix)) == $endpointSuffix;
    }

    /**
     * Indicate if the URI ends with the given endpoint
     *
     * @param string $uri
     * @param string $endpointURI
     * @return boolean
     */
    private static function getSlashedURI(): string
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Remove everything after "?" and "#"
        $symbols = ['?', '#'];
        foreach ($symbols as $symbol) {
            $symbolPos = strpos($uri, $symbol);
            if ($symbolPos !== false) {
                $uri = substr($uri, 0, $symbolPos);
            }
        }
        return trailingslashit($uri);
    }

    /**
     * Process the request to find out if it is any of the endpoints
     *
     * @return void
     */
    public static function parseRequest(): void
    {
        // If it /index.php?graphql_by_pop then it comes from GraphiQL
        $doingGraphQL = false;
        $doingREST = false;
        $doingAPI = false;
        if (isset($_GET['graphql_by_pop'])) {
            $doingGraphQL = true;
        } else {
            // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
            $uri = self::getSlashedURI();
            $doingGraphQL = self::endsWithString($uri, self::$GRAPHQL_ENDPOINT);
            $doingREST = self::endsWithString($uri, self::$REST_ENDPOINT);
            $doingAPI = self::endsWithString($uri, self::$API_ENDPOINT);
        }

        // Set the params on the request, to emulate that they were added by the user (that's how it works, lah)
        if ($doingGraphQL || $doingREST || $doingAPI) {
            $_REQUEST[\GD_URLPARAM_SCHEME] = \POP_SCHEME_API;
            if ($doingGraphQL) {
                $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = GraphQLDataStructureFormatter::getName();
            } elseif ($doingREST) {
                $_REQUEST[\GD_URLPARAM_DATASTRUCTURE] = RESTDataStructureFormatter::getName();
            }
        }
    }

    public static function addRewriteEndpoints()
    {
        add_rewrite_endpoint(self::$GRAPHQL_ENDPOINT, EP_ALL);
        add_rewrite_endpoint(self::$REST_ENDPOINT, EP_ALL);
        add_rewrite_endpoint(self::$API_ENDPOINT, EP_ALL);
    }

    public static function addQueryVar($query_vars)
    {
        $query_vars[] = self::$GRAPHQL_ENDPOINT;
        $query_vars[] = self::$REST_ENDPOINT;
        $query_vars[] = self::$API_ENDPOINT;
        return $query_vars;
    }
}
