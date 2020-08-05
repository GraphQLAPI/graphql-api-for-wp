<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;

/**
 * The cache modules have different behavior depending on the environment:
 * - "development": visible, disabled by default
 * - "production": hidden, enabled by default
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CacheFunctionalityModuleResolver extends AbstractCacheFunctionalityModuleResolver
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
                        $moduleRegistry->getInverseDependency(AccessControlFunctionalityModuleResolver::PUBLIC_PRIVATE_SCHEMA),
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
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
}
