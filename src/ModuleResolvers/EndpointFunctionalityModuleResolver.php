<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;

class EndpointFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    // public const MAIN = Plugin::NAMESPACE . '\main';
    public const SINGLE_ENDPOINT = Plugin::NAMESPACE . '\single-endpoint';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\persisted-queries';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\custom-endpoints';
    public const API_HIERARCHY = Plugin::NAMESPACE . '\api-hierarchy';

    /**
     * Setting options
     */
    public const OPTION_PATH = 'path';

    public static function getModulesToResolve(): array
    {
        return [
            // self::MAIN,
            self::SINGLE_ENDPOINT,
            self::PERSISTED_QUERIES,
            self::CUSTOM_ENDPOINTS,
            self::API_HIERARCHY,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return 'endpoint';
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::PERSISTED_QUERIES:
            case self::SINGLE_ENDPOINT:
            case self::CUSTOM_ENDPOINTS:
                return [];
            case self::API_HIERARCHY:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    // public function canBeDisabled(string $module): bool
    // {
    //     switch ($module) {
    //         case self::MAIN:
    //             return false;
    //     }
    //     return parent::canBeDisabled($module);
    // }

    // public function isHidden(string $module): bool
    // {
    //     switch ($module) {
    //         case self::MAIN:
    //             return true;
    //     }
    //     return parent::isHidden($module);
    // }

    public function getName(string $module): string
    {
        $names = [
            // self::MAIN => \__('Main', 'graphql-api'),
            self::SINGLE_ENDPOINT => \__('Single Endpoint', 'graphql-api'),
            self::PERSISTED_QUERIES => \__('Persisted Queries', 'graphql-api'),
            self::CUSTOM_ENDPOINTS => \__('Custom Endpoints', 'graphql-api'),
            self::API_HIERARCHY => \__('API Hierarchy', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            // case self::MAIN:
            //     return \__('Artificial module for defining the main settings', 'graphql-api');
            case self::SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Expose a single GraphQL endpoint under <code>%s</code>, with unrestricted access', 'graphql-api'),
                    GraphQLEndpointForWPComponentConfiguration::getGraphQLAPIEndpoint()
                );
            case self::PERSISTED_QUERIES:
                return \__('Expose predefined responses through a custom URL, akin to using GraphQL queries to publish REST endpoints', 'graphql-api');
            case self::CUSTOM_ENDPOINTS:
                return \__('Expose different subsets of the schema for different targets, such as users (clients, employees, etc), applications (website, mobile app, etc), context (weekday, weekend, etc), and others', 'graphql-api');
            case self::API_HIERARCHY:
                return \__('Create a hierarchy of API endpoints extending from other endpoints, and inheriting their properties', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SINGLE_ENDPOINT:
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
            self::SINGLE_ENDPOINT => [
                self::OPTION_PATH => '/graphql/',
            ],
            self::CUSTOM_ENDPOINTS => [
                self::OPTION_PATH => 'graphql',
            ],
            self::PERSISTED_QUERIES => [
                self::OPTION_PATH => 'graphql-query',
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
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::SINGLE_ENDPOINT) {
            $option = self::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Endpoint path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to expose the single GraphQL endpoint', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::CUSTOM_ENDPOINTS) {
            $option = self::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Base path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL base path to expose the Custom Endpoint', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::PERSISTED_QUERIES) {
            $option = self::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Base path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL base path to expose the Persisted Query', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        }
        return $moduleSettings;
    }
}
