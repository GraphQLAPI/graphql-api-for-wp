<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use PoP\APIEndpoints\EndpointUtils;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\API\Configuration\Request;

abstract class AbstractEndpointHandler
{
    /**
     * Endpoint
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Provide the endpoint
     *
     * @var string
     */
    abstract protected function getEndpoint(): string;

    /**
     * Initialize the client
     *
     * @return void
     */
    public function initialize(): void
    {
        /**
         * Subject to the endpoint having been defined
         */
        if ($this->endpoint = $this->getEndpoint()) {
            // Make sure the endpoint has trailing "/" on both ends
            $this->endpoint = EndpointUtils::slashURI($this->endpoint);
            /**
             * Register the endpoints
             */
            \add_action(
                'init',
                [$this, 'addRewriteEndpoints']
            );
            \add_filter(
                'query_vars',
                [$this, 'addQueryVar'],
                10,
                1
            );
        }
    }

    /**
     * Indicate if the endpoint has been requested
     *
     * @return void
     */
    protected function isEndpointRequested(): bool
    {
        // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
        $uri = EndpointUtils::removeMarkersFromURI($_SERVER['REQUEST_URI']);
        return EndpointUtils::doesURIEndWith($uri, $this->endpoint);
    }

    /**
     * If use full permalink, the endpoint must be the whole URL.
     * Otherwise, it can be attached at the end of some other URI (eg: a custom post)
     *
     * @return boolean
     */
    protected function useFullPermalink(): bool
    {
        return false;
    }

    /**
     * Add the endpoints to WordPress
     *
     * @return void
     */
    public function addRewriteEndpoints()
    {
        /**
         * The mask indicates where to apply the endpoint rewriting
         * @see https://codex.wordpress.org/Rewrite_API/add_rewrite_endpoint
         */
        $mask = $this->useFullPermalink() ? constant('EP_ROOT') : constant('EP_ALL');

        // The endpoint passed to `add_rewrite_endpoint` cannot have "/" on either end, or it doesn't work
        \add_rewrite_endpoint(trim($this->endpoint, '/'), $mask);
    }

    /**
     * Add the endpoint query vars
     *
     * @param array $query_vars
     * @return void
     */
    public function addQueryVar($query_vars)
    {
        $query_vars[] = $this->endpoint;
        return $query_vars;
    }
}
