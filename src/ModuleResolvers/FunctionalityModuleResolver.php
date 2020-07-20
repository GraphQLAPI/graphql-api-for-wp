<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use PoP\AccessControl\Schema\SchemaModes;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use PoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;
use PoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;

class FunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    // public const MAIN = Plugin::NAMESPACE . '\main';
    public const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\schema-editing-access';
    public const SINGLE_ENDPOINT = Plugin::NAMESPACE . '\single-endpoint';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\persisted-queries';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\custom-endpoints';
    public const GRAPHIQL_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\graphiql-for-single-endpoint';
    public const GRAPHIQL_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\graphiql-for-custom-endpoints';
    public const INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT = Plugin::NAMESPACE . '\interactive-schema-for-single-endpoint';
    public const INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\interactive-schema-for-custom-endpoints';
    public const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public const SCHEMA_NAMESPACING = Plugin::NAMESPACE . '\schema-namespacing';
    public const PUBLIC_PRIVATE_SCHEMA = Plugin::NAMESPACE . '\public-private-schema';
    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\access-control';
    public const ACCESS_CONTROL_RULE_DISABLE_ACCESS = Plugin::NAMESPACE . '\access-control-rule-disable-access';
    public const ACCESS_CONTROL_RULE_USER_STATE = Plugin::NAMESPACE . '\access-control-rule-user-state';
    public const ACCESS_CONTROL_RULE_USER_ROLES = Plugin::NAMESPACE . '\access-control-rule-user-roles';
    public const ACCESS_CONTROL_RULE_USER_CAPABILITIES = Plugin::NAMESPACE . '\access-control-rule-user-capabilities';
    public const CACHE_CONTROL = Plugin::NAMESPACE . '\cache-control';
    public const FIELD_DEPRECATION = Plugin::NAMESPACE . '\field-deprecation';
    public const EXCERPT_AS_DESCRIPTION = Plugin::NAMESPACE . '\excerpt-as-description';
    public const API_HIERARCHY = Plugin::NAMESPACE . '\api-hierarchy';
    public const LOW_LEVEL_QUERY_EDITING = Plugin::NAMESPACE . '\low-level-query-editing';
    public const GRAPHIQL_EXPLORER = Plugin::NAMESPACE . '\graphiql-explorer';
    public const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';

    /**
     * Setting options
     */
    public const OPTION_EDITING_ACCESS_SCHEME = 'editing-access-scheme';
    public const OPTION_PATH = 'path';
    public const OPTION_SCHEMA_CONFIGURATION_ID = 'schema-configuration-id';
    public const OPTION_USE_NAMESPACING = 'use-namespacing';
    public const OPTION_MODE = 'mode';
    public const OPTION_ENABLE_GRANULAR = 'granular';
    public const OPTION_MAX_AGE = 'max-age';

    /**
     * Setting option values
     */
    public const OPTION_VALUE_NO_VALUE_ID = 0;

    public static function getModulesToResolve(): array
    {
        return [
            // self::MAIN,
            self::SINGLE_ENDPOINT,
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT,
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
            self::PERSISTED_QUERIES,
            self::CUSTOM_ENDPOINTS,
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS,
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS,
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_NAMESPACING,
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            self::ACCESS_CONTROL_RULE_USER_STATE,
            self::ACCESS_CONTROL_RULE_USER_ROLES,
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
            self::PUBLIC_PRIVATE_SCHEMA,
            self::CACHE_CONTROL,
            self::FIELD_DEPRECATION,
            self::API_HIERARCHY,
            self::LOW_LEVEL_QUERY_EDITING,
            self::GRAPHIQL_EXPLORER,
            self::SCHEMA_EDITING_ACCESS,
            self::EXCERPT_AS_DESCRIPTION,
            self::WELCOME_GUIDES,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::PERSISTED_QUERIES:
            case self::SINGLE_ENDPOINT:
            case self::CUSTOM_ENDPOINTS:
            case self::LOW_LEVEL_QUERY_EDITING:
            case self::EXCERPT_AS_DESCRIPTION:
                return [];
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return [
                    [
                        self::SINGLE_ENDPOINT,
                    ],
                ];
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return [
                    [
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_CONFIGURATION:
            case self::WELCOME_GUIDES:
            case self::API_HIERARCHY:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_NAMESPACING:
            case self::ACCESS_CONTROL:
            case self::FIELD_DEPRECATION:
                return [
                    [
                        self::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::CACHE_CONTROL:
                return [
                    [
                        self::SCHEMA_CONFIGURATION,
                    ],
                    [
                        self::PERSISTED_QUERIES,
                    ],
                ];
            case self::PUBLIC_PRIVATE_SCHEMA:
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
            case self::ACCESS_CONTROL_RULE_USER_STATE:
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
            case self::GRAPHIQL_EXPLORER:
                return [
                    [
                        self::PERSISTED_QUERIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        switch ($module) {
            case self::WELCOME_GUIDES:
                /**
                 * WordPress 5.5 or above, or Gutenberg 8.2 or above
                 */
                return
                    \is_wp_version_compatible('5.5') ||
                    (
                        defined('GUTENBERG_VERSION') &&
                        \version_compare(constant('GUTENBERG_VERSION'), '8.2', '>=')
                    );
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                /**
                 * Permalink structure must be enabled
                 */
                return !empty(\get_option('permalink_structure'));
        }
        return parent::areRequirementsSatisfied($module);
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

    public function isHidden(string $module): bool
    {
        switch ($module) {
            // case self::MAIN:
            case self::WELCOME_GUIDES:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        $names = [
            // self::MAIN => \__('Main', 'graphql-api'),
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'graphql-api'),
            self::SINGLE_ENDPOINT => \__('Single Endpoint', 'graphql-api'),
            self::PERSISTED_QUERIES => \__('Persisted Queries', 'graphql-api'),
            self::CUSTOM_ENDPOINTS => \__('Custom Endpoints', 'graphql-api'),
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => \__('GraphiQL for Single Endpoint', 'graphql-api'),
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS => \__('GraphiQL for Custom Endpoints', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => \__('Interactive Schema for Single Endpoint', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS => \__('Interactive Schema for Custom Endpoints', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'graphql-api'),
            self::SCHEMA_NAMESPACING => \__('Schema Namespacing', 'graphql-api'),
            self::PUBLIC_PRIVATE_SCHEMA => \__('Public/Private Schema', 'graphql-api'),
            self::ACCESS_CONTROL => \__('Access Control', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS => \__('Access Control Rule: Disable Access', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_STATE => \__('Access Control Rule: User State', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_ROLES => \__('Access Control Rule: User Roles', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES => \__('Access Control Rule: User Capabilities', 'graphql-api'),
            self::CACHE_CONTROL => \__('Cache Control', 'graphql-api'),
            self::FIELD_DEPRECATION => \__('Field Deprecation', 'graphql-api'),
            self::EXCERPT_AS_DESCRIPTION => \__('Excerpt as Description', 'graphql-api'),
            self::API_HIERARCHY => \__('API Hierarchy', 'graphql-api'),
            self::LOW_LEVEL_QUERY_EDITING => \__('Low-Level Query Editing', 'graphql-api'),
            self::GRAPHIQL_EXPLORER => \__('GraphiQL Explorer', 'graphql-api'),
            self::WELCOME_GUIDES => \__('Welcome Guides', 'graphql-api'),
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
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public GraphiQL client available under <code>%s</code>, to execute queries against the single endpoint. It requires pretty permalinks enabled', 'graphql-api'),
                    GraphQLClientsForWPComponentConfiguration::getGraphiQLClientEndpoint()
                );
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own GraphiQL client, to execute queries against them', 'graphql-api');
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public Interactive Schema client available under <code>%s</code>, to visualize the schema accessible through the single endpoint. It requires pretty permalinks enabled', 'graphql-api'),
                    GraphQLClientsForWPComponentConfiguration::getVoyagerClientEndpoint()
                );
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own Interactive schema client, to visualize the custom schema subset', 'graphql-api');
            case self::SCHEMA_CONFIGURATION:
                return \__('Customize the schema accessible to different Custom Endpoints and Persisted Queries, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema', 'graphql-api');
            case self::SCHEMA_NAMESPACING:
                return \__('Automatically namespace types and interfaces with a vendor/project name, to avoid naming collisions', 'graphql-api');
            case self::PUBLIC_PRIVATE_SCHEMA:
                return \__('Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)', 'graphql-api');
            case self::ACCESS_CONTROL:
                return \__('Set-up rules to define who can access the different fields and directives from a schema', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
                return \__('Remove access to the fields and directives', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_STATE:
                return \__('Allow or reject access to the fields and directives based on the user being logged-in or not', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
                return \__('Allow or reject access to the fields and directives based on the user having a certain role', 'graphql-api');
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return \__('Allow or reject access to the fields and directives based on the user having a certain capability', 'graphql-api');
            case self::CACHE_CONTROL:
                return \__('Provide HTTP Caching for Persisted Queries, sending the Cache-Control header with a max-age value calculated from all fields in the query', 'graphql-api');
            case self::FIELD_DEPRECATION:
                return \__('Deprecate fields, and explain how to replace them, through a user interface', 'graphql-api');
            case self::EXCERPT_AS_DESCRIPTION:
                return \__('Provide a description of the different entities (Custom Endpoints, Persisted Queries, and others) through their excerpt', 'graphql-api');
            case self::API_HIERARCHY:
                return \__('Create a hierarchy of API endpoints extending from other endpoints, and inheriting their properties', 'graphql-api');
            case self::LOW_LEVEL_QUERY_EDITING:
                return \__('Have access to schema-configuration low-level directives when editing GraphQL queries in the admin', 'graphql-api');
            case self::GRAPHIQL_EXPLORER:
                return \__('Add the Explorer widget to the GraphiQL client when creating Persisted Queries, to simplify coding the query (by point-and-clicking on the fields)', 'graphql-api');
            case self::WELCOME_GUIDES:
                return sprintf(
                    \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. <em>It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above</em>', 'graphql-api'),
                    '5.4',
                    '6.1'
                );
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SINGLE_ENDPOINT:
            case self::LOW_LEVEL_QUERY_EDITING:
            case self::SCHEMA_NAMESPACING:
            case self::FIELD_DEPRECATION:
            case self::WELCOME_GUIDES:
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
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => [
                self::OPTION_PATH => '/graphiql/',
            ],
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => [
                self::OPTION_PATH => '/schema/',
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
            self::SCHEMA_NAMESPACING => [
                self::OPTION_USE_NAMESPACING => false,
            ],
            self::PUBLIC_PRIVATE_SCHEMA => [
                self::OPTION_MODE => SchemaModes::PUBLIC_SCHEMA_MODE,
                self::OPTION_ENABLE_GRANULAR => true,
            ],
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
                    $option,
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
                    $option,
                ),
                Properties::TITLE => \__('Endpoint path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to expose the single GraphQL endpoint', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::GRAPHIQL_FOR_SINGLE_ENDPOINT) {
            $option = self::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
                ),
                Properties::TITLE => \__('Client path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to access the public GraphiQL client', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT) {
            $option = self::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
                ),
                Properties::TITLE => \__('Client path', 'graphql-api'),
                Properties::DESCRIPTION => \__('URL path to access the public Interactive Schema client', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
            ];
        } elseif ($module == self::CUSTOM_ENDPOINTS) {
            $option = self::OPTION_PATH;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
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
                    $option,
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
            if (
                $customPosts = \get_posts([
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
                    $option,
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
        } elseif ($module == self::SCHEMA_NAMESPACING) {
            $option = self::OPTION_USE_NAMESPACING;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
                ),
                Properties::TITLE => \__('Use namespacing?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Automatically namespace types and interfaces in the schema', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module == self::PUBLIC_PRIVATE_SCHEMA) {
            $whereModules = [];
            $dependencyModules = [
                self::SCHEMA_CONFIGURATION,
                self::ACCESS_CONTROL,
            ];
            foreach ($dependencyModules as $dependencyModule) {
                $whereModules[] = '▹ ' . $this->getName($dependencyModule);
            }
            $option = self::OPTION_MODE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
                ),
                Properties::TITLE => \__('Default visibility', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Visibility to use for fields and directives in the schema when option <code>"%s"</code> is selected (in %s)', 'graphql-api'),
                    ComponentConfiguration::getSettingsValueLabel(),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModules
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
                    SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
                ],
            ];
            $option = self::OPTION_ENABLE_GRANULAR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
                ),
                Properties::TITLE => \__('Enable granular control?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Enable to select the visibility for a set of fields/directives when editing the Access Control List', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module == self::CACHE_CONTROL) {
            $option = self::OPTION_MAX_AGE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
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
