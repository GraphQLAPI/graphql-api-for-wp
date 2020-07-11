<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;

class PredefinedForProductionFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const CONFIGURATION_CACHE = Plugin::NAMESPACE . '\configuration-cache';
    public const SCHEMA_CACHE = Plugin::NAMESPACE . '\schema-cache';

    public static function getModulesToResolve(): array
    {
        return [
            self::CONFIGURATION_CACHE,
            self::SCHEMA_CACHE,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::CONFIGURATION_CACHE:
                return [];
            case self::SCHEMA_CACHE:
                $moduleRegistry = ModuleRegistryFacade::getInstance();
                return [
                    [
                        self::CONFIGURATION_CACHE,
                    ],
                    [
                        $moduleRegistry->getInverseDependency(FunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA),
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function isHidden(string $module): bool
    {
        switch ($module) {
            case self::CONFIGURATION_CACHE:
            case self::SCHEMA_CACHE:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::CONFIGURATION_CACHE => \__('Configuration Cache', 'graphql-api'),
            self::SCHEMA_CACHE => \__('Schema Cache', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CONFIGURATION_CACHE:
                return \__('Cache the generated application configuration to disk', 'graphql-api');
            case self::SCHEMA_CACHE:
                return \__('Cache the generated schema to disk', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_CACHE:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }
}
