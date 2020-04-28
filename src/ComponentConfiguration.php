<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\ComponentModel\ComponentConfiguration\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $addExcerptAsDescription;
    private static $groupFieldsUnderTypeForPrint;
    private static $getEmptyLabel;
    private static $getSettingsValueLabel;
    private static $useGraphiQLWithExplorer;
    private static $getEndpointSlugBase;
    private static $getPersistedQuerySlugBase;

    public static function addExcerptAsDescription(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_EXCERPT_AS_DESCRIPTION;
        $selfProperty = &self::$addExcerptAsDescription;
        $callback = [Environment::class, 'addExcerptAsDescription'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function groupFieldsUnderTypeForPrint(): bool
    {
        // Define properties
        $envVariable = Environment::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT;
        $selfProperty = &self::$groupFieldsUnderTypeForPrint;
        $callback = [Environment::class, 'groupFieldsUnderTypeForPrint'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getEmptyLabel(): string
    {
        // Define properties
        $envVariable = Environment::EMPTY_LABEL;
        $selfProperty = &self::$getEmptyLabel;
        $callback = [Environment::class, 'getEmptyLabel'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getSettingsValueLabel(): string
    {
        // Define properties
        $envVariable = Environment::SETTINGS_VALUE_LABEL;
        $selfProperty = &self::$getSettingsValueLabel;
        $callback = [Environment::class, 'getSettingsValueLabel'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function useGraphiQLWithExplorer(): bool
    {
        // Define properties
        $envVariable = Environment::USE_GRAPHIQL_WITH_EXPLORER;
        $selfProperty = &self::$useGraphiQLWithExplorer;
        $callback = [Environment::class, 'useGraphiQLWithExplorer'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getEndpointSlugBase(): string
    {
        // Define properties
        $envVariable = Environment::ENDPOINT_SLUG_BASE;
        $selfProperty = &self::$getEndpointSlugBase;
        $callback = [Environment::class, 'getEndpointSlugBase'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }

    public static function getPersistedQuerySlugBase(): string
    {
        // Define properties
        $envVariable = Environment::PERSISTED_QUERY_SLUG_BASE;
        $selfProperty = &self::$getPersistedQuerySlugBase;
        $callback = [Environment::class, 'getPersistedQuerySlugBase'];

        // Initialize property from the environment/hook
        self::maybeInitEnvironmentVariable(
            $envVariable,
            $selfProperty,
            $callback
        );
        return $selfProperty;
    }
}
