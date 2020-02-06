<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;

class Endpoints {

    /**
     * GraphQL endpoint
     *
     * @var string
     */
    public $GRAPHQL_ENDPOINT;
    /**
     * REST endpoint
     *
     * @var string
     */
    public $REST_ENDPOINT;
    /**
     * Native API endpoint
     *
     * @var string
     */
    public $API_ENDPOINT;

    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function init(): void
    {
        /**
         * Define the endpoints
         */
        $this->GRAPHQL_ENDPOINT = apply_filters(
            'graphql_by_pop:graphql_endpoint',
            'api/graphql'
        );
        $this->REST_ENDPOINT = apply_filters(
            'graphql_by_pop:rest_endpoint',
            'api/rest'
        );
        $this->API_ENDPOINT = apply_filters(
            'graphql_by_pop:api_endpoint',
            'api'
        );

        /**
         * Register the endpoints
         */
        add_action(
            'init',
            [$this, 'addRewriteEndpoints']
        );
        add_filter(
            'query_vars',
            [$this, 'addQueryVar'],
            10,
            1
        );

        /**
         * Process the request to find out if it is any of the endpoints
         */
        add_action(
            'parse_request',
            [$this, 'parseRequest']
        );
    }

    /**
     * Indicate if the URI ends with the given endpoint
     *
     * @param string $uri
     * @param string $endpointURI
     * @return boolean
     */
    private function endsWithString(string $uri, string $endpointURI): bool
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
    private function getSlashedURI(): string
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
    public function parseRequest(): void
    {
        // If it /index.php?graphql_by_pop then it comes from GraphiQL
        $doingGraphQL = false;
        $doingREST = false;
        $doingAPI = false;
        if (isset($_GET['graphql_by_pop'])) {
            $doingGraphQL = true;
        } else {
            // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
            $uri = $this->getSlashedURI();
            $doingGraphQL = $this->endsWithString($uri, $this->GRAPHQL_ENDPOINT);
            $doingREST = $this->endsWithString($uri, $this->REST_ENDPOINT);
            $doingAPI = $this->endsWithString($uri, $this->API_ENDPOINT);
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

    /**
     * Add the endpoints to WordPress
     *
     * @return void
     */
    public function addRewriteEndpoints()
    {
        add_rewrite_endpoint($this->GRAPHQL_ENDPOINT, EP_ALL);
        add_rewrite_endpoint($this->REST_ENDPOINT, EP_ALL);
        add_rewrite_endpoint($this->API_ENDPOINT, EP_ALL);
    }

    /**
     * Add the endpoint query vars
     *
     * @param array $query_vars
     * @return void
     */
    public function addQueryVar($query_vars)
    {
        $query_vars[] = $this->GRAPHQL_ENDPOINT;
        $query_vars[] = $this->REST_ENDPOINT;
        $query_vars[] = $this->API_ENDPOINT;
        return $query_vars;
    }
}
