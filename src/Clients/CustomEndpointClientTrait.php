<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use GraphQLAPI\GraphQLAPI\General\RequestParams;
use PoP\API\Configuration\Request as APIRequest;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

trait CustomEndpointClientTrait
{
    /**
     * Endpoint URL
     *
     * @return string
     */
    protected function getEndpointURL(): string
    {
        /**
         * If accessing from Nginx, the server_name might point to localhost
         * instead of the actual server domain. So use the user-requested host
         */
        $fullURL = \fullUrl(true);
        // Remove the ?view=..., and maybe add ?use_namespace=true
        $endpointURL = \remove_query_arg(RequestParams::VIEW, $fullURL);
        if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
            $endpointURL = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        }
        return $endpointURL;
    }
}
