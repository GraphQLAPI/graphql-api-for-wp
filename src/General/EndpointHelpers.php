<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\General;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\API\Configuration\Request as APIRequest;
use PoP\GraphQL\Configuration\Request as GraphQLRequest;

class EndpointHelpers
{
    public static function getAdminGraphQLEndpoint(): string
    {
        $endpoint = \admin_url(sprintf(
            'edit.php?page=%s&%s=%s',
            Menu::NAME,
            RequestParams::ACTION,
            RequestParams::ACTION_EXECUTE_QUERY
        ));
        // Add /?edit_schema=1 so the query-type directives are also visible
        if (ComponentConfiguration::enableLowLevelSchemaEditing()) {
            $endpoint = \add_query_arg(GraphQLRequest::URLPARAM_EDIT_SCHEMA, true, $endpoint);
        }
        // If namespaced, add /?use_namespace=1 to the endpoint
        if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
            $endpoint = \add_query_arg(APIRequest::URLPARAM_USE_NAMESPACE, true, $endpoint);
        }
        return $endpoint;
    }
}
