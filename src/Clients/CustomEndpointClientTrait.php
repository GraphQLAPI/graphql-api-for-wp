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
        $endpointURL = \remove_query_arg(RequestParams::VIEW, \fullUrl());
        if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
            $endpointURL = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        }
        return $endpointURL;
    }
}
