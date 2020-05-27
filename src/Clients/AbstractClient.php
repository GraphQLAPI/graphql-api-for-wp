<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use PoP\APIEndpointsForWP\EndpointUtils;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\API\Configuration\Request;

abstract class AbstractClient
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
            /**
             * Process the request to find out if it is any of the endpoints
             */
            \add_action(
                'parse_request',
                [$this, 'parseRequest']
            );
        }
    }

    /**
     * Vendor Dir Path
     *
     * @return string
     */
    abstract protected function getVendorDirPath(): string;
    /**
     * JavaScript file name
     *
     * @return string
     */
    abstract protected function getJSFilename(): string;
    /**
     * HTML file name
     *
     * @return string
     */
    protected function getIndexFilename(): string
    {
        return 'index.html';
    }
    /**
     * Assets folder name
     *
     * @return string
     */
    protected function getAssetsDirname(): string
    {
        return 'assets';
    }

    /**
     * HTML to print the client
     *
     * @return string
     */
    public function getClientHTML(): string
    {
        // Read from the static HTML files and replace their endpoints
        $dirPath = $this->getVendorDirPath();
        $file = \GRAPHQL_API_DIR . $dirPath . '/' . $this->getIndexFilename();
        $fileContents = \file_get_contents($file, true);
        $jsFileName = $this->getJSFilename();
        /**
         * Relative asset paths do not work, since the location of the JS/CSS file is
         * different than the URL under which the client is accessed.
         * Then add the URL to the plugin to all assets (they are all located under "assets/...")
         */
        $fileContents = \str_replace(
            '"' . $this->getAssetsDirname() . '/',
            '"' . \trim(\GRAPHQL_API_URL, '/') . $dirPath . '/' . $this->getAssetsDirname() . '/',
            $fileContents
        );

        // Current domain
        $domain = \getDomain(\fullUrl());
        $endpointURL = $domain . '/api/graphql/';
        if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
            $endpointURL = \add_query_arg(Request::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        }
        // Modify the endpoint, as a param to the script
        $fileContents = \str_replace(
            '/' . $jsFileName . '?',
            '/' . $jsFileName . '?endpoint=' . urlencode($endpointURL) . '&',
            $fileContents
        );

        return $fileContents;
    }

    /**
     * Indicate if the endpoint has been requested
     *
     * @return void
     */
    protected function isClientRequested(): bool
    {
        // Check if the URL ends with either /api/graphql/ or /api/rest/ or /api/
        $uri = EndpointUtils::removeMarkersFromURI($_SERVER['REQUEST_URI']);
        return EndpointUtils::doesURIEndWith($uri, $this->endpoint);
    }

    /**
     * If the endpoint for the client is requested, print the client and exit
     *
     * @return void
     */
    public function parseRequest(): void
    {
        if ($this->isClientRequested()) {
            echo $this->getClientHTML();
            die;
        }
    }

    /**
     * Add the endpoints to WordPress
     *
     * @return void
     */
    public function addRewriteEndpoints()
    {
        \add_rewrite_endpoint($this->endpoint, constant('EP_ALL'));
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
