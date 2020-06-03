<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static $getModuleURLBase;
    private static $addExcerptAsDescription;
    private static $groupFieldsUnderTypeForPrint;
    private static $getEmptyLabel;
    private static $getSettingsValueLabel;
    private static $useGraphiQLWithExplorer;
    private static $getCustomEndpointSlugBase;
    private static $getPersistedQuerySlugBase;
    private static $getSchemaEditorAccessScheme;

    /**
     * URL base for the module, pointing to graphql-api.com
     *
     * @return string
     */
    public static function getModuleURLBase(): string
    {
        // Define properties
        $envVariable = Environment::MODULE_URL_BASE;
        $selfProperty = &self::$getModuleURLBase;
        $defaultValue = 'https://graphql-api.com/modules/';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

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
        // $defaultValue = \__('As defined in the General Settings', 'graphql-api');
        $defaultValue = \__('Default', 'graphql-api');

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
     * The slug to use as base when accessing the custom endpoint
     *
     * @return string
     */
    public static function getCustomEndpointSlugBase(): string
    {
        // Define properties
        $envVariable = Environment::ENDPOINT_SLUG_BASE;
        $selfProperty = &self::$getCustomEndpointSlugBase;
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
}
