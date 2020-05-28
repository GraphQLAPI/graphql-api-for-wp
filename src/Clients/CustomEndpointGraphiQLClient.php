<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Clients;

use PoP\GraphQLClientsForWP\Clients\GraphiQLClient;

class CustomEndpointGraphiQLClient extends GraphiQLClient
{
    use CustomEndpointClientTrait;
}
