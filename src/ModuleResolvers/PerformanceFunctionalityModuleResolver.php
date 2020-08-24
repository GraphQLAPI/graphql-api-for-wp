<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

/**
 * The cache modules have different behavior depending on the environment:
 * - "development": visible, disabled by default
 * - "production": hidden, enabled by default
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class PerformanceFunctionalityModuleResolver extends AbstractCacheFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const CACHE_CONTROL = Plugin::NAMESPACE . '\cache-control';
    public const CONFIGURATION_CACHE = Plugin::NAMESPACE . '\configuration-cache';
    public const SCHEMA_CACHE = Plugin::NAMESPACE . '\schema-cache';

    /**
     * Setting options
     */
    public const OPTION_MAX_AGE = 'max-age';

    public static function getModulesToResolve(): array
    {
        return [
            self::CACHE_CONTROL,
            self::CONFIGURATION_CACHE,
            self::SCHEMA_CACHE,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ],
                ];
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
            self::CACHE_CONTROL => \__('Cache Control', 'graphql-api'),
            self::CONFIGURATION_CACHE => \__('Configuration Cache', 'graphql-api'),
            self::SCHEMA_CACHE => \__('Schema Cache', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return \__('Provide HTTP Caching for Persisted Queries, sending the Cache-Control header with a max-age value calculated from all fields in the query', 'graphql-api');
            case self::CONFIGURATION_CACHE:
                return \__('Cache the generated application configuration to disk', 'graphql-api');
            case self::SCHEMA_CACHE:
                return \__('Cache the generated schema to disk', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     *
     * @param string $module
     * @param string $option
     * @return mixed Anything the setting might be: an array|string|bool|int|null
     */
    public function getSettingsDefaultValue(string $module, string $option)
    {
        $defaultValues = [
            self::CACHE_CONTROL => [
                self::OPTION_MAX_AGE => 86400, // 1 day
            ],
        ];
        return $defaultValues[$module][$option];
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @param string $module
     * @return array
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::CACHE_CONTROL) {
            $option = self::OPTION_MAX_AGE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default max-age', 'graphql-api'),
                Properties::DESCRIPTION => \__('Default max-age value (in seconds) for the Cache-Control header, for all fields and directives in the schema', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => 0,
            ];
        }
        return $moduleSettings;
    }
}
