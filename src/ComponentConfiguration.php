<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\ComponentModel\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $addExcerptAsDescription;
    private static $groupFieldsUnderTypeForPrint;

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
}

