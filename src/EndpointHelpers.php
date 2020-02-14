<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

class EndpointHelpers {

    public static function getGraphQLEndpoint(bool $slashed = false): string
    {
        $endpoint = 'api/graphql';
        if ($slashed) {
            return '/'.$endpoint.'/';
        }
        return $endpoint;
    }

    public static function getGraphQLEndpointURL(): string
    {
        $endpoint = self::getGraphQLEndpoint(true);
        $endpointURL = site_url().$endpoint;
        if (true) {
            $endpointURL = add_query_arg('use_namespace', true, $endpointURL);
        }

        return $endpointURL;
    }
}
