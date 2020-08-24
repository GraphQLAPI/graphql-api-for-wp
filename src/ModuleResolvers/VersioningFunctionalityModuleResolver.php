<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class VersioningFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const FIELD_DEPRECATION = Plugin::NAMESPACE . '\field-deprecation';

    public static function getModulesToResolve(): array
    {
        return [
            self::FIELD_DEPRECATION,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return 'versioning';
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::FIELD_DEPRECATION:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::FIELD_DEPRECATION => \__('Field Deprecation', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::FIELD_DEPRECATION:
                return \__('Deprecate fields, and explain how to replace them, through a user interface', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::FIELD_DEPRECATION:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }
}
