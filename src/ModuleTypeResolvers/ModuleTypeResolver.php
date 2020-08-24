<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleTypeResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\AbstractModuleTypeResolver;

/**
 * All module types used in this plugin. Others can be registered by extensions
 */
class ModuleTypeResolver extends AbstractModuleTypeResolver
{
    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\access-control';
    public const CLIENT = Plugin::NAMESPACE . '\client';
    public const ENDPOINT = Plugin::NAMESPACE . '\endpoint';
    public const FUNCTIONALITY = Plugin::NAMESPACE . '\functionality';
    public const OPERATIONAL = Plugin::NAMESPACE . '\operational';
    public const PERFORMANCE = Plugin::NAMESPACE . '\performance';
    public const PLUGIN_MANAGEMENT = Plugin::NAMESPACE . '\plugin-management';
    public const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public const SCHEMA_TYPE = Plugin::NAMESPACE . '\schema-type';
    public const USER_INTERFACE = Plugin::NAMESPACE . '\user-interface';
    public const VERSIONING = Plugin::NAMESPACE . '\versioning';

    public static function getModuleTypesToResolve(): array
    {
        return [
            self::ACCESS_CONTROL,
            self::CLIENT,
            self::ENDPOINT,
            self::FUNCTIONALITY,
            self::OPERATIONAL,
            self::PERFORMANCE,
            self::PLUGIN_MANAGEMENT,
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_TYPE,
            self::USER_INTERFACE,
            self::VERSIONING,
        ];
    }

    public function getDescription(string $moduleType): string
    {
        $descriptions = [
            self::ACCESS_CONTROL => \__('', 'graphql-api'),
            self::CLIENT => \__('', 'graphql-api'),
            self::ENDPOINT => \__('', 'graphql-api'),
            self::FUNCTIONALITY => \__('', 'graphql-api'),
            self::OPERATIONAL => \__('', 'graphql-api'),
            self::PERFORMANCE => \__('', 'graphql-api'),
            self::PLUGIN_MANAGEMENT => \__('', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => \__('', 'graphql-api'),
            self::SCHEMA_TYPE => \__('', 'graphql-api'),
            self::USER_INTERFACE => \__('', 'graphql-api'),
            self::VERSIONING => \__('', 'graphql-api'),
        ];
        return $descriptions[$moduleType] ?? '';
    }
}
