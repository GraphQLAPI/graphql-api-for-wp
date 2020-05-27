<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;

class GraphiQLClient extends AbstractClient
{
    protected function getEndpoint(): string
    {
        return ComponentConfiguration::getGraphiQLClientEndpoint();
    }
    protected function getVendorDirPath(): string
    {
        return '/vendor/leoloso/pop-graphiql';
    }
    protected function getJSFilename(): string
    {
        return 'graphiql.js';
    }
}
