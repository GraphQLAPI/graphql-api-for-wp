<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

use PoP\ComponentModel\AbstractComponentConfiguration;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private static $addExcerptAsDescription;

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
}

