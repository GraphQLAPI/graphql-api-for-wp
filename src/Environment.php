<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

class Environment
{
    public const ADD_EXCERPT_AS_DESCRIPTION = 'ADD_EXCERPT_AS_DESCRIPTION';
    public const GROUP_FIELDS_UNDER_TYPE_FOR_PRINT = 'GROUP_FIELDS_UNDER_TYPE_FOR_PRINT';
    public const EMPTY_LABEL = 'EMPTY_LABEL';
    public const SETTINGS_VALUE_LABEL = 'SETTINGS_VALUE_LABEL';
    public const USE_GRAPHIQL_WITH_EXPLORER = 'USE_GRAPHIQL_WITH_EXPLORER';
    public const ENDPOINT_SLUG_BASE = 'ENDPOINT_SLUG_BASE';
    public const PERSISTED_QUERY_SLUG_BASE = 'PERSISTED_QUERY_SLUG_BASE';
    public const SCHEMA_EDITOR_ACCESS_SCHEME = 'SCHEMA_EDITOR_ACCESS_SCHEME';
    public const ENABLE_LOW_LEVEL_SCHEMA_EDITING = 'ENABLE_LOW_LEVEL_SCHEMA_EDITING';

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
     * The label to show when the value comes from the settings
     *
     * @return boolean
     */
    public static function getSettingsValueLabel(): string
    {
        return isset($_ENV[self::SETTINGS_VALUE_LABEL]) ? $_ENV[self::SETTINGS_VALUE_LABEL] : \__('As defined in the General Settings', 'graphql-api');
    }

    /**
     * Use GraphiQL with the Explorer
     *
     * @return boolean
     */
    public static function useGraphiQLWithExplorer(): bool
    {
        return isset($_ENV[self::USE_GRAPHIQL_WITH_EXPLORER]) ? strtolower($_ENV[self::USE_GRAPHIQL_WITH_EXPLORER]) == 'true' : true;
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

    /**
     * If `"admin"`, only the admin can compose a GraphQL query and endpoint
     * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
     * but not publish the query, Editor role can publish it, etc)
     *
     * @return string
     */
    public static function getSchemaEditorAccessScheme(): ?string
    {
        return $_ENV[self::SCHEMA_EDITOR_ACCESS_SCHEME];
    }

    /**
     * If `true`, it makes Schema-type directives available in the GraphiQL editor
     *
     * @return boolean
     */
    public static function enableLowLevelSchemaEditing(): bool
    {
        return isset($_ENV[self::ENABLE_LOW_LEVEL_SCHEMA_EDITING]) ? strtolower($_ENV[self::ENABLE_LOW_LEVEL_SCHEMA_EDITING]) == 'true' : false;
    }
}
