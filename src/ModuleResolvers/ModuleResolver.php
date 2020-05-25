<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;

class ModuleResolver extends AbstractModuleResolver
{
    public const MAIN = Plugin::NAMESPACE . '\main';
    public const SINGLE_ENDPOINT = Plugin::NAMESPACE . '\single-endpoint';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\persisted-queries';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\custom-endpoints';
    public const GRAPHIQL_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\graphiql-for-custom-endpoints';
    public const INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\interactive-schema-for-custom-endpoints';
    public const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\access-control';
    public const ACCESS_CONTROL_RULE_DISABLE_ACCESS = Plugin::NAMESPACE . '\access-control-rule-disable-access';
    public const ACCESS_CONTROL_RULE_USER_STATE = Plugin::NAMESPACE . '\access-control-rule-user-state';
    public const ACCESS_CONTROL_RULE_USER_ROLES = Plugin::NAMESPACE . '\access-control-rule-user-roles';
    public const ACCESS_CONTROL_RULE_USER_CAPABILITIES = Plugin::NAMESPACE . '\access-control-rule-user-capabilities';
    public const CACHE_CONTROL = Plugin::NAMESPACE . '\cache-control';
    public const FIELD_DEPRECATION = Plugin::NAMESPACE . '\field-deprecation';
    public const GRAPHIQL_EXPLORER = Plugin::NAMESPACE . '\graphiql-explorer';
    public const WELCOME_GUIDES = Plugin::NAMESPACE . '\welcome-guides';
    public const DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE = Plugin::NAMESPACE . '\directive-set-convert-lower-uppercase';
    public const SCHEMA_POST_TYPE = Plugin::NAMESPACE . '\schema-post-type';
    public const SCHEMA_COMMENT_TYPE = Plugin::NAMESPACE . '\schema-comment-type';
    public const SCHEMA_USER_TYPE = Plugin::NAMESPACE . '\schema-user-type';
    public const SCHEMA_PAGE_TYPE = Plugin::NAMESPACE . '\schema-page-type';
    public const SCHEMA_MEDIA_TYPE = Plugin::NAMESPACE . '\schema-media-type';

    public static function getModulesToResolve(): array
    {
        return [
            self::MAIN,
            self::PERSISTED_QUERIES,
            self::SINGLE_ENDPOINT,
            self::CUSTOM_ENDPOINTS,
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS,
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS,
            self::SCHEMA_CONFIGURATION,
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            self::ACCESS_CONTROL_RULE_USER_STATE,
            self::ACCESS_CONTROL_RULE_USER_ROLES,
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
            self::CACHE_CONTROL,
            self::FIELD_DEPRECATION,
            self::GRAPHIQL_EXPLORER,
            self::WELCOME_GUIDES,
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE,
            self::SCHEMA_POST_TYPE,
            self::SCHEMA_COMMENT_TYPE,
            self::SCHEMA_USER_TYPE,
            self::SCHEMA_PAGE_TYPE,
            self::SCHEMA_MEDIA_TYPE,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::PERSISTED_QUERIES:
                return [
                    [
                        self::MAIN,
                    ],
                ];
            case self::SINGLE_ENDPOINT:
                return [
                    [
                        self::MAIN,
                    ],
                ];
            case self::CUSTOM_ENDPOINTS:
                return [
                    [
                        self::MAIN,
                    ],
                ];
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return [
                    [
                        self::PERSISTED_QUERIES,
                    ],
                ];
            case self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS:
                return [
                    [
                        self::PERSISTED_QUERIES,
                    ],
                ];
            case self::SCHEMA_CONFIGURATION:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::ACCESS_CONTROL:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
            case self::ACCESS_CONTROL_RULE_USER_STATE:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
            case self::ACCESS_CONTROL_RULE_USER_ROLES:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
            case self::ACCESS_CONTROL_RULE_USER_CAPABILITIES:
                return [
                    [
                        self::ACCESS_CONTROL,
                    ],
                ];
            case self::CACHE_CONTROL:
                return [
                    [
                        self::PERSISTED_QUERIES,
                    ],
                ];
            case self::FIELD_DEPRECATION:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::GRAPHIQL_EXPLORER:
                return [
                    [
                        self::PERSISTED_QUERIES,
                    ],
                ];
            case self::WELCOME_GUIDES:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                        self::SINGLE_ENDPOINT,
                    ],
                ];
            case self::SCHEMA_POST_TYPE:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                        self::SINGLE_ENDPOINT,
                    ],
                ];
            case self::SCHEMA_COMMENT_TYPE:
                return [
                    [
                        self::SCHEMA_POST_TYPE,
                    ],
                ];
            case self::SCHEMA_USER_TYPE:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                        self::SINGLE_ENDPOINT,
                    ],
                ];
            case self::SCHEMA_PAGE_TYPE:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                        self::SINGLE_ENDPOINT,
                    ],
                ];
            case self::SCHEMA_MEDIA_TYPE:
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                        self::SINGLE_ENDPOINT,
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
                 * WordPress 5.4 or above, or Gutenberg 6.1 or above
                 */
                return
                    \is_wp_version_compatible('5.4') ||
                    (
                        defined('GUTENBERG_VERSION') &&
                        \version_compare(constant('GUTENBERG_VERSION'), '6.1', '>=')
                    );
        }
        return parent::areRequirementsSatisfied($module);
    }

    public function isHidden(string $module): bool
    {
        switch ($module) {
            case self::MAIN:
            case self::SCHEMA_CONFIGURATION:
            case self::ACCESS_CONTROL_RULE_DISABLE_ACCESS:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::MAIN => \__('Main', 'graphql-api'),
            self::SINGLE_ENDPOINT => \__('Single Endpoint', 'graphql-api'),
            self::PERSISTED_QUERIES => \__('Persisted Queries', 'graphql-api'),
            self::CUSTOM_ENDPOINTS => \__('Custom Endpoints', 'graphql-api'),
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS => \__('GraphiQL for Custom Endpoints', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS => \__('Interactive Schema for Custom Endpoints', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'graphql-api'),
            self::ACCESS_CONTROL => \__('Access Control', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS => \__('Access Control Rule: Disable Access', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_STATE => \__('Access Control Rule: User State', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_ROLES => \__('Access Control Rule: User Roles', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES => \__('Access Control Rule: User Capabilities', 'graphql-api'),
            self::CACHE_CONTROL => \__('Cache Control', 'graphql-api'),
            self::FIELD_DEPRECATION => \__('Field Deprecation', 'graphql-api'),
            self::GRAPHIQL_EXPLORER => \__('GraphiQL Explorer', 'graphql-api'),
            self::WELCOME_GUIDES => \__('Welcome Guides', 'graphql-api'),
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE => \__('Directive Set: Convert Lower/Uppercase', 'graphql-api'),
            self::SCHEMA_POST_TYPE => \__('Schema Post Type', 'graphql-api'),
            self::SCHEMA_COMMENT_TYPE => \__('Schema Comment Type', 'graphql-api'),
            self::SCHEMA_USER_TYPE => \__('Schema User Type', 'graphql-api'),
            self::SCHEMA_PAGE_TYPE => \__('Schema Page Type', 'graphql-api'),
            self::SCHEMA_MEDIA_TYPE => \__('Schema Media Type', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        $descriptions = [
            self::MAIN => \__('Main functionality module, can\'t be disabled but is required for defining the main settings', 'graphql-api'),
            self::SINGLE_ENDPOINT => \sprintf(
                \__('Make data queryable through a single GraphQL endpoint under <code>%s</code>, with unrestricted access', 'graphql-api'),
                '/graphql/'
            ),
            self::PERSISTED_QUERIES => \__('Expose a predefined response by publishing persisted GraphQL queries, and accessing them under their permalink', 'graphql-api'),
            self::CUSTOM_ENDPOINTS => \__('Make data queryable through custom endpoints, each accepting a different configuration (access control, cache control, etc)', 'graphql-api'),
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS => \__('Enable custom endpoints to be attached a GraphiQL client, to execute queries against them. It depends on module "Custom Endpoints"', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS => \__('Enable custom endpoints to be attached an Interactive schema client, to visualize the schema from the custom endpoint after applying all the access control rules. It depends on module "Custom Endpoints"', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => \__('Configure the different elements that modify the behavior of the schema (access control, cache control, etc)', 'graphql-api'),
            self::ACCESS_CONTROL => \__('Set-up rules to define who can access the different fields and directives from a schema', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS => \__('Remove access to the fields and directives. It depends on module "Access Control"', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_STATE => \__('Allow or reject access to the fields and directives based on the user being logged-in or not. It depends on module "Access Control"', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_ROLES => \__('Allow or reject access to the fields and directives based on the user having a certain role. It depends on module "Access Control"', 'graphql-api'),
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES => \__('Allow or reject access to the fields and directives based on the user having a certain capability. It depends on module "Access Control"', 'graphql-api'),
            self::CACHE_CONTROL => \__('Provide HTTP Caching for Persisted Queries: Cache the response by setting the Cache-Control max-age value, calculated from all fields involved in the query. It depends on module "Persisted Queries"', 'graphql-api'),
            self::FIELD_DEPRECATION => \__('User interface to deprecate fields', 'graphql-api'),
            self::GRAPHIQL_EXPLORER => \__('Attach the Explorer widget to the GraphiQL client, to create queries by point-and-clicking on the fields', 'graphql-api'),
            self::WELCOME_GUIDES => sprintf(
                \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities. It requires WordPress version \'%s\' or above, or Gutenberg version \'%s\' or above', 'graphql-api'),
                '5.4',
                '6.1'
            ),
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE => \__('Set of directives to manipulate strings: <code>@upperCase</code>, <code>@lowerCase</code> and <code>@titleCase</code>', 'graphql-api'),
            self::SCHEMA_POST_TYPE => \__('Enable querying for posts in the schema', 'graphql-api'),
            self::SCHEMA_COMMENT_TYPE => \__('Enable querying for comments in the schema. It depends on module "Schema Post Type"', 'graphql-api'),
            self::SCHEMA_USER_TYPE => \__('Enable querying for users in the schema', 'graphql-api'),
            self::SCHEMA_PAGE_TYPE => \__('Enable querying for pages in the schema', 'graphql-api'),
            self::SCHEMA_MEDIA_TYPE => \__('Enable querying for media items in the schema', 'graphql-api'),
        ];
        return $descriptions[$module] ?? parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SINGLE_ENDPOINT:
                return false;
        }
        return parent::isEnabledByDefault($module);
    }
}
