<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\General;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\API\Configuration\Request;

class EndpointHelpers
{
    public static function getGraphQLEndpoint(): string
    {
        $endpoint = \admin_url(sprintf(
            'edit.php?page=%s&%s=%s',
            Menu::NAME,
            RequestParams::ACTION,
            RequestParams::ACTION_EXECUTE_QUERY
        ));
        if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
            $endpoint = \add_query_arg(Request::URLPARAM_USE_NAMESPACE, true, $endpoint);
        }
        return $endpoint;
    }
}
