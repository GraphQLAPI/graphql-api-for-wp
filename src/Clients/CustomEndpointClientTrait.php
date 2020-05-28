<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use PoP\API\Configuration\Request;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use GraphQLAPI\GraphQLAPI\General\RequestParams;

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
            $endpointURL = \add_query_arg(Request::URLPARAM_USE_NAMESPACE, true, $endpointURL);
        }
        return $endpointURL;
    }
}
