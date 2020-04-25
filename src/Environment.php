<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

class Environment
{
    public const ADD_EXCERPT_AS_DESCRIPTION = 'ADD_EXCERPT_AS_DESCRIPTION';
    public const GROUP_FIELDS_UNDER_TYPE_FOR_PRINT = 'GROUP_FIELDS_UNDER_TYPE_FOR_PRINT';
    public const EMPTY_LABEL = 'EMPTY_LABEL';
    public const USE_GRAPHIQL_WITH_EXPLORER = 'USE_GRAPHIQL_WITH_EXPLORER';
    public const ENDPOINT_SLUG_BASE = 'ENDPOINT_SLUG_BASE';
    public const PERSISTED_QUERY_SLUG_BASE = 'PERSISTED_QUERY_SLUG_BASE';

    /**
     * Print the excerpt as description in the custom post types
     *
     * @return boolean
     */
    public static function addExcerptAsDescription(): bool
    {
        return isset($_ENV[self::ADD_EXCERPT_AS_DESCRIPTION]) ? strtolower($_ENV[self::ADD_EXCERPT_AS_DESCRIPTION]) == "true" : true;
    }

    /**
     * Group the fields under the type when printing it for the user
     *
     * @return boolean
     */
    public static function groupFieldsUnderTypeForPrint(): bool
    {
        return isset($_ENV[self::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT]) ? strtolower($_ENV[self::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT]) == "true" : true;
    }

    /**
     * The label to show when the value is empty
     *
     * @return boolean
     */
    public static function getEmptyLabel(): string
    {
        return isset($_ENV[self::EMPTY_LABEL]) ? $_ENV[self::EMPTY_LABEL] : \__('---', 'graphql-api');
    }

    /**
     * Use GraphiQL with the Explorer
     *
     * @return boolean
     */
    public static function useGraphiQLWithExplorer(): bool
    {
        return isset($_ENV[self::USE_GRAPHIQL_WITH_EXPLORER]) ? $_ENV[self::USE_GRAPHIQL_WITH_EXPLORER] : true;
    }

    /**
     * The slug to use as base when accessing the endpoint
     *
     * @return string
     */
    public static function getEndpointSlugBase(): string
    {
        return $_ENV[self::ENDPOINT_SLUG_BASE] ? $_ENV[self::ENDPOINT_SLUG_BASE] : 'graphql';
    }

    /**
     * The slug to use as base when accessing the persisted query
     *
     * @return string
     */
    public static function getPersistedQuerySlugBase(): string
    {
        return $_ENV[self::PERSISTED_QUERY_SLUG_BASE] ? $_ENV[self::PERSISTED_QUERY_SLUG_BASE] : 'graphql-query';
    }
}
