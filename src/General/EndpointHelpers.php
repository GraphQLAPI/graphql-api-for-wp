<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\General;

class EndpointHelpers
{

    public static function getGraphQLEndpoint(bool $slashed = false, bool $useNamespace = true): string
    {
        $endpoint = 'api/graphql';
        if ($slashed) {
            return '/' . $endpoint . '/';
        }
        if ($useNamespace && true) {
            $endpoint = \add_query_arg('use_namespace', true, $endpoint);
        }
        return $endpoint;
    }

    public static function getGraphQLEndpointURL(): string
    {
        $endpoint = self::getGraphQLEndpoint(true, true);
        return \site_url() . $endpoint;
    }
}
