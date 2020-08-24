<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class OperationalFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const SCHEMA_NAMESPACING = Plugin::NAMESPACE . '\schema-namespacing';
    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\multiple-query-execution';
    public const REMOVE_IF_NULL_DIRECTIVE = Plugin::NAMESPACE . '\remove-if-null-directive';
    public const PROACTIVE_FEEDBACK = Plugin::NAMESPACE . '\proactive-feedback';

    /**
     * Setting options
     */
    public const OPTION_USE_NAMESPACING = 'use-namespacing';

    public static function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_NAMESPACING,
            self::MULTIPLE_QUERY_EXECUTION,
            self::REMOVE_IF_NULL_DIRECTIVE,
            self::PROACTIVE_FEEDBACK,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleSubtype(string $module): ?string
    {
        return 'pioneering';
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_NAMESPACING:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::MULTIPLE_QUERY_EXECUTION:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::REMOVE_IF_NULL_DIRECTIVE:
            case self::PROACTIVE_FEEDBACK:
                return [];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::SCHEMA_NAMESPACING => \__('Schema Namespacing', 'graphql-api'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution', 'graphql-api'),
            self::REMOVE_IF_NULL_DIRECTIVE => \__('Remove if Null', 'graphql-api'),
            self::PROACTIVE_FEEDBACK => \__('Proactive Feedback', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_NAMESPACING:
                return \__('Automatically namespace types and interfaces with a vendor/project name, to avoid naming collisions', 'graphql-api');
            case self::MULTIPLE_QUERY_EXECUTION:
                return \__('Execute multiple GraphQL queries in a single operation', 'graphql-api');
            case self::REMOVE_IF_NULL_DIRECTIVE:
                return \__('Addition of <code>@removeIfNull</code> directive, to remove an output from the response if it is <code>null</code>', 'graphql-api');
            case self::PROACTIVE_FEEDBACK:
                return \__('Usage of the top-level entry <code>extensions</code> to send deprecations, warnings, logs, notices and traces in the response to the query', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_NAMESPACING:
            case self::MULTIPLE_QUERY_EXECUTION:
            case self::REMOVE_IF_NULL_DIRECTIVE:
                return false;
        }
        return parent::isEnabledByDefault($module);
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
            self::SCHEMA_NAMESPACING => [
                self::OPTION_USE_NAMESPACING => false,
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
        if ($module == self::SCHEMA_NAMESPACING) {
            $option = self::OPTION_USE_NAMESPACING;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use namespacing?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Automatically namespace types and interfaces in the schema', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
