<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;

class FunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    // public const MAIN = Plugin::NAMESPACE . '\main';
    public const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\schema-editing-access';
    public const SINGLE_ENDPOINT = Plugin::NAMESPACE . '\single-endpoint';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\persisted-queries';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\custom-endpoints';
    public const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public const API_HIERARCHY = Plugin::NAMESPACE . '\api-hierarchy';

    /**
     * Setting options
     */
    public const OPTION_EDITING_ACCESS_SCHEME = 'editing-access-scheme';
    public const OPTION_PATH = 'path';
    public const OPTION_SCHEMA_CONFIGURATION_ID = 'schema-configuration-id';

    /**
     * Setting option values
     */
    public const OPTION_VALUE_NO_VALUE_ID = 0;

    public static function getModulesToResolve(): array
    {
        return [
            // self::MAIN,
            self::SINGLE_ENDPOINT,
            self::PERSISTED_QUERIES,
            self::CUSTOM_ENDPOINTS,
            self::SCHEMA_CONFIGURATION,
            self::API_HIERARCHY,
            self::SCHEMA_EDITING_ACCESS,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::PERSISTED_QUERIES:
            case self::SINGLE_ENDPOINT:
            case self::CUSTOM_ENDPOINTS:
                return [];
            case self::SCHEMA_CONFIGURATION:
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
    //         case self::SCHEMA_EDITING_ACCESS:
    //         // case self::MAIN:
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
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'graphql-api'),
            self::SINGLE_ENDPOINT => \__('Single Endpoint', 'graphql-api'),
            self::PERSISTED_QUERIES => \__('Persisted Queries', 'graphql-api'),
            self::CUSTOM_ENDPOINTS => \__('Custom Endpoints', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'graphql-api'),
            self::API_HIERARCHY => \__('API Hierarchy', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            // case self::MAIN:
            //     return \__('Artificial module for defining the main settings', 'graphql-api');
            case self::SCHEMA_EDITING_ACCESS:
                return \__('Grant access to users other than admins to edit the GraphQL schema', 'graphql-api');
            case self::SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Expose a single GraphQL endpoint under <code>%s</code>, with unrestricted access', 'graphql-api'),
                    GraphQLEndpointForWPComponentConfiguration::getGraphQLAPIEndpoint()
                );
            case self::PERSISTED_QUERIES:
                return \__('Expose predefined responses through a custom URL, akin to using GraphQL queries to publish REST endpoints', 'graphql-api');
            case self::CUSTOM_ENDPOINTS:
                return \__('Expose different subsets of the schema for different targets, such as users (clients, employees, etc), applications (website, mobile app, etc), context (weekday, weekend, etc), and others', 'graphql-api');
            case self::SCHEMA_CONFIGURATION:
                return \__('Customize the schema accessible to different Custom Endpoints and Persisted Queries, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema', 'graphql-api');
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
            self::SCHEMA_EDITING_ACCESS => [
                self::OPTION_EDITING_ACCESS_SCHEME => UserAuthorization::ACCESS_SCHEME_ADMIN_ONLY,
            ],
            self::SINGLE_ENDPOINT => [
                self::OPTION_PATH => '/graphql/',
            ],
            self::CUSTOM_ENDPOINTS => [
                self::OPTION_PATH => 'graphql',
            ],
            self::PERSISTED_QUERIES => [
                self::OPTION_PATH => 'graphql-query',
            ],
            self::SCHEMA_CONFIGURATION => [
                self::OPTION_SCHEMA_CONFIGURATION_ID => self::OPTION_VALUE_NO_VALUE_ID,
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
        if ($module == self::SCHEMA_EDITING_ACCESS) {
            /**
             * Write Access Scheme
             * If `"admin"`, only the admin can compose a GraphQL query and endpoint
             * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
             * but not publish the query, Editor role can publish it, etc)
             */
            $option = self::OPTION_EDITING_ACCESS_SCHEME;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Editing Access Scheme', 'graphql-api'),
                Properties::DESCRIPTION => \__('Scheme to decide which users can edit the schema (Persisted Queries, Custom Endpoints and related post types) and with what permissions', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    UserAuthorization::ACCESS_SCHEME_ADMIN_ONLY => \__('Admin user(s) only', 'graphql-api'),
                    UserAuthorization::ACCESS_SCHEME_POST => \__('Use same access workflow as for editing posts', 'graphql-api'),
                ],
            ];
        } elseif ($module == self::SINGLE_ENDPOINT) {
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
        } elseif ($module == self::SCHEMA_CONFIGURATION) {
            $whereModules = [];
            $maybeWhereModules = [
                self::CUSTOM_ENDPOINTS,
                self::PERSISTED_QUERIES,
            ];
            foreach ($maybeWhereModules as $maybeWhereModule) {
                if ($moduleRegistry->isModuleEnabled($maybeWhereModule)) {
                    $whereModules[] = '▹ ' . $this->getName($maybeWhereModule);
                }
            }
            // Build all the possible values by fetching all the Schema Configuration posts
            $possibleValues = [
                self::OPTION_VALUE_NO_VALUE_ID => \__('None', 'graphql-api'),
            ];
            if ($customPosts = \get_posts([
                    'posts_per_page' => -1,
                    'post_type' => GraphQLSchemaConfigurationPostType::POST_TYPE,
                    'post_status' => 'publish',
                ])
            ) {
                foreach ($customPosts as $customPost) {
                    $possibleValues[$customPost->ID] = $customPost->post_title;
                }
            }
            $option = self::OPTION_SCHEMA_CONFIGURATION_ID;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default Schema Configuration', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Schema Configuration to use when option <code>"Default"</code> is selected (in %s)', 'graphql-api'),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModules
                    )
                ),
                Properties::TYPE => Properties::TYPE_INT,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        }
        return $moduleSettings;
    }
}
