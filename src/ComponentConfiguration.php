<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoP\APIEndpoints\EndpointUtils;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $addExcerptAsDescription;
    private static $groupFieldsUnderTypeForPrint;
    private static $getEmptyLabel;
    private static $getSettingsValueLabel;
    private static $useGraphiQLWithExplorer;
    private static $getEndpointSlugBase;
    private static $getPersistedQuerySlugBase;
    private static $getSchemaEditorAccessScheme;
    private static $enableLowLevelSchemaEditing;
    private static $graphiQLClientEndpoint;
    private static $voyagerClientEndpoint;

    /**
     * Print the excerpt as description in the custom post types
     *
     * @return boolean
     */
    public static function addExcerptAsDescription(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_EXCERPT_AS_DESCRIPTION;
        $selfProperty = &self::$addExcerptAsDescription;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Group the fields under the type when printing it for the user
     *
     * @return boolean
     */
    public static function groupFieldsUnderTypeForPrint(): bool
    {
        // Define properties
        $envVariable = Environment::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT;
        $selfProperty = &self::$groupFieldsUnderTypeForPrint;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * The label to show when the value is empty
     *
     * @return boolean
     */
    public static function getEmptyLabel(): string
    {
        // Define properties
        $envVariable = Environment::EMPTY_LABEL;
        $selfProperty = &self::$getEmptyLabel;
        $defaultValue = \__('---', 'graphql-api');

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * The label to show when the value comes from the settings
     *
     * @return boolean
     */
    public static function getSettingsValueLabel(): string
    {
        // Define properties
        $envVariable = Environment::SETTINGS_VALUE_LABEL;
        $selfProperty = &self::$getSettingsValueLabel;
        $defaultValue = \__('As defined in the General Settings', 'graphql-api');

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * Use GraphiQL with the Explorer
     *
     * @return boolean
     */
    public static function useGraphiQLWithExplorer(): bool
    {
        // Define properties
        $envVariable = Environment::USE_GRAPHIQL_WITH_EXPLORER;
        $selfProperty = &self::$useGraphiQLWithExplorer;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * The slug to use as base when accessing the endpoint
     *
     * @return string
     */
    public static function getEndpointSlugBase(): string
    {
        // Define properties
        $envVariable = Environment::ENDPOINT_SLUG_BASE;
        $selfProperty = &self::$getEndpointSlugBase;
        $defaultValue = 'graphql';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * The slug to use as base when accessing the persisted query
     *
     * @return string
     */
    public static function getPersistedQuerySlugBase(): string
    {
        // Define properties
        $envVariable = Environment::PERSISTED_QUERY_SLUG_BASE;
        $selfProperty = &self::$getPersistedQuerySlugBase;
        $defaultValue = 'graphql-query';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * If `"admin"`, only the admin can compose a GraphQL query and endpoint
     * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
     * but not publish the query, Editor role can publish it, etc)
     *
     * @return string
     */
    public static function getSchemaEditorAccessScheme(): string
    {
        // Define properties
        $envVariable = Environment::SCHEMA_EDITOR_ACCESS_SCHEME;
        $selfProperty = &self::$getSchemaEditorAccessScheme;
        $defaultValue = UserAuthorization::ACCESS_SCHEME_ADMIN_ONLY;

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * If `true`, it makes Schema-type directives available in the GraphiQL editor
     *
     * @return boolean
     */
    public static function enableLowLevelSchemaEditing(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_LOW_LEVEL_SCHEMA_EDITING;
        $selfProperty = &self::$enableLowLevelSchemaEditing;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * GraphiQL client endpoint, to be executed against the GraphQL single endpoint
     *
     * @return string
     */
    public static function getGraphiQLClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHIQL_CLIENT_ENDPOINT;
        $selfProperty = &self::$graphiQLClientEndpoint;
        $defaultValue = '/graphiql/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Voyager client endpoint, to be executed against the GraphQL single endpoint
     *
     * @return string
     */
    public static function getVoyagerClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::VOYAGER_CLIENT_ENDPOINT;
        $selfProperty = &self::$voyagerClientEndpoint;
        $defaultValue = '/schema/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
