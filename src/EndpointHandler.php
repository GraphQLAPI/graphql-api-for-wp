<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use Leoloso\PoPAPIEndpointsForWP\EndpointHandler as PoPEndpointHandler;

class EndpointHandler {

    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function init(): void
    {
        /**
         * Process the request to find out if it is any of the endpoints
         */
        add_action(
            'parse_request',
            [$this, 'parseRequest']
        );
    }

    /**
     * Process the request to find out if it is any of the endpoints
     *
     * @return void
     */
    public function parseRequest(): void
    {
        // If it /index.php?graphql_by_pop then it comes from GraphiQL
        if (isset($_GET['graphql_by_pop'])) {
            PoPEndpointHandler::setDoingGraphQL();
        }
    }
}
