<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

class EndpointHelpers {

    public static function getGraphQLEndpoint(): string
    {
        return 'api/graphql';
    }

    public static function getGraphQLEndpointURL(): string
    {
        $endpoint = self::getGraphQLEndpoint();
        $endpointURL = trailingslashit(trailingslashit(site_url()).$endpoint);
        if (true) {
            $endpointURL = add_query_arg('use_namespace', true, $endpointURL);
        }

        return $endpointURL;
    }
}
