<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\Pages\TypeResolvers\PageTypeResolver;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\Users\TypeResolvers\UserTypeResolver;
use GraphQLAPI\GraphQLAPI\General\LocaleUtils;
use PoP\Media\TypeResolvers\MediaTypeResolver;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use PoP\Taxonomies\TypeResolvers\TagTypeResolver;
use PoP\Comments\TypeResolvers\CommentTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use PoP\UsefulDirectives\DirectiveResolvers\LowerCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\TitleCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\UpperCaseStringDirectiveResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;
use PoP\APIEndpointsForWP\ComponentConfiguration as APIEndpointsForWPComponentConfiguration;
use PoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;

class ModuleResolver extends AbstractModuleResolver
{
    use HasMarkdownDocumentationModuleResolverTrait;

    // use HasMarkdownDocumentationModuleResolverTrait {
    //     HasMarkdownDocumentationModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    // }

    public const MAIN = Plugin::NAMESPACE . '\main';
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
    public const SCHEMA_CACHE = Plugin::NAMESPACE . '\schema-cache';
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
    public const DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE = Plugin::NAMESPACE . '\directive-set-convert-lower-uppercase';
    public const SCHEMA_POST_TYPE = Plugin::NAMESPACE . '\schema-post-type';
    public const SCHEMA_COMMENT_TYPE = Plugin::NAMESPACE . '\schema-comment-type';
    public const SCHEMA_USER_TYPE = Plugin::NAMESPACE . '\schema-user-type';
    public const SCHEMA_PAGE_TYPE = Plugin::NAMESPACE . '\schema-page-type';
    public const SCHEMA_MEDIA_TYPE = Plugin::NAMESPACE . '\schema-media-type';
    public const SCHEMA_TAXONOMY_TYPE = Plugin::NAMESPACE . '\schema-taxonomy-type';

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
    public const OPTION_POST_DEFAULT_LIMIT = 'post-default-limit';
    public const OPTION_POST_MAX_LIMIT = 'post-max-limit';
    public const OPTION_CONTENT_DEFAULT_LIMIT = 'content-default-limit';
    public const OPTION_CONTENT_MAX_LIMIT = 'content-max-limit';
    public const OPTION_USER_DEFAULT_LIMIT = 'user-default-limit';
    public const OPTION_USER_MAX_LIMIT = 'user-max-limit';
    public const OPTION_TAG_DEFAULT_LIMIT = 'tag-default-limit';
    public const OPTION_TAG_MAX_LIMIT = 'tag-max-limit';
    public const OPTION_PAGE_DEFAULT_LIMIT = 'page-default-limit';
    public const OPTION_PAGE_MAX_LIMIT = 'page-max-limit';

    /**
     * Setting option values
     */
    public const OPTION_VALUE_NO_VALUE_ID = 0;

    public static function getModulesToResolve(): array
    {
        return [
            self::MAIN,
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
            self::SCHEMA_CACHE,
            self::GRAPHIQL_EXPLORER,
            self::EXCERPT_AS_DESCRIPTION,
            self::WELCOME_GUIDES,
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE,
            self::SCHEMA_USER_TYPE,
            self::SCHEMA_PAGE_TYPE,
            self::SCHEMA_MEDIA_TYPE,
            self::SCHEMA_POST_TYPE,
            self::SCHEMA_COMMENT_TYPE,
            self::SCHEMA_TAXONOMY_TYPE,
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
            case self::SCHEMA_CACHE:
                $moduleRegistry = ModuleRegistryFacade::getInstance();
                return [
                    [
                        $moduleRegistry->getInverseDependency(self::PUBLIC_PRIVATE_SCHEMA),
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
            case self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE:
            case self::SCHEMA_POST_TYPE:
            case self::SCHEMA_USER_TYPE:
            case self::SCHEMA_PAGE_TYPE:
            case self::SCHEMA_MEDIA_TYPE:
                return [
                    [
                        self::SINGLE_ENDPOINT,
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_COMMENT_TYPE:
            case self::SCHEMA_TAXONOMY_TYPE:
                return [
                    [
                        self::SCHEMA_POST_TYPE,
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
            case self::SCHEMA_CACHE:
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
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => \__('GraphiQL for Single Endpoint', 'graphql-api'),
            self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS => \__('GraphiQL for Custom Endpoints', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => \__('Interactive Schema for Single Endpoint', 'graphql-api'),
            self::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS => \__('Interactive Schema for Custom Endpoints', 'graphql-api'),
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'graphql-api'),
            self::SCHEMA_NAMESPACING => \__('Schema Namespacing', 'graphql-api'),
            self::PUBLIC_PRIVATE_SCHEMA => \__('Public/Private Schema', 'graphql-api'),
            self::SCHEMA_CACHE => \__('Schema Cache', 'graphql-api'),
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
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE => \__('Directive Set: Convert Lower/Uppercase', 'graphql-api'),
            self::SCHEMA_POST_TYPE => \__('Schema Post Type', 'graphql-api'),
            self::SCHEMA_COMMENT_TYPE => \__('Schema Comment Type', 'graphql-api'),
            self::SCHEMA_USER_TYPE => \__('Schema User Type', 'graphql-api'),
            self::SCHEMA_PAGE_TYPE => \__('Schema Page Type', 'graphql-api'),
            self::SCHEMA_MEDIA_TYPE => \__('Schema Media Type', 'graphql-api'),
            self::SCHEMA_TAXONOMY_TYPE => \__('Schema Taxonomy Type', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::MAIN:
                return \__('Artificial module for defining the main settings', 'graphql-api');
            case self::SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Expose a single GraphQL endpoint under <code>%s</code>, with unrestricted access', 'graphql-api'),
                    APIEndpointsForWPComponentConfiguration::getGraphQLAPIEndpoint()
                );
            case self::PERSISTED_QUERIES:
                return \__('Expose predefined responses through a custom URL, akin to using GraphQL queries to publish REST endpoints', 'graphql-api');
            case self::CUSTOM_ENDPOINTS:
                return \__('Expose different subsets of the schema for different targets, such as users (clients, employees, etc), applications (website, mobile app, etc), context (weekday, weekend, etc), and others', 'graphql-api');
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public GraphiQL client available under <code>%s</code>, to execute queries against the single endpoint', 'graphql-api'),
                    GraphQLClientsForWPComponentConfiguration::getGraphiQLClientEndpoint()
                );
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own GraphiQL client, to execute queries against them', 'graphql-api');
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public Interactive Schema client available under <code>%s</code>, to visualize the schema accessible through the single endpoint', 'graphql-api'),
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
            case self::SCHEMA_CACHE:
                return \__('Cache the schema to avoid generating it on runtime, and speed-up the server\'s response', 'graphql-api');
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
            case self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE:
                return sprintf(
                    \__('Set of directives to manipulate strings: <code>@%s</code>, <code>@%s</code> and <code>@%s</code>', 'graphql-api'),
                    UpperCaseStringDirectiveResolver::getDirectiveName(),
                    LowerCaseStringDirectiveResolver::getDirectiveName(),
                    TitleCaseStringDirectiveResolver::getDirectiveName()
                );
            case self::SCHEMA_POST_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    PostTypeResolver::NAME,
                );
            case self::SCHEMA_USER_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    UserTypeResolver::NAME,
                );
            case self::SCHEMA_PAGE_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    PageTypeResolver::NAME,
                );
            case self::SCHEMA_MEDIA_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    MediaTypeResolver::NAME,
                );
            case self::SCHEMA_COMMENT_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    CommentTypeResolver::NAME,
                );
            case self::SCHEMA_TAXONOMY_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    TagTypeResolver::NAME,
                );
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SINGLE_ENDPOINT:
            case self::LOW_LEVEL_QUERY_EDITING:
            case self::SCHEMA_CACHE:
            case self::SCHEMA_NAMESPACING:
            case self::FIELD_DEPRECATION:
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
            self::MAIN => [
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
                self::OPTION_MAX_AGE => 60,
            ],
            self::SCHEMA_POST_TYPE => [
                self::OPTION_POST_DEFAULT_LIMIT => 10,
                self::OPTION_POST_MAX_LIMIT => 100,
                self::OPTION_CONTENT_DEFAULT_LIMIT => 10,
                self::OPTION_CONTENT_MAX_LIMIT => 100,
            ],
            self::SCHEMA_USER_TYPE => [
                self::OPTION_USER_DEFAULT_LIMIT => 10,
                self::OPTION_USER_MAX_LIMIT => 100,
            ],
            self::SCHEMA_PAGE_TYPE => [
                self::OPTION_PAGE_DEFAULT_LIMIT => 10,
                self::OPTION_PAGE_MAX_LIMIT => 100,
            ],
            self::SCHEMA_TAXONOMY_TYPE => [
                self::OPTION_TAG_DEFAULT_LIMIT => 50,
                self::OPTION_TAG_MAX_LIMIT => 500,
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
        // Common variables to set the limit on the schema types
        $limitArg = 'limit';
        $unlimitedValue = -1;
        $defaultLimitMessagePlaceholder = \__('Number of results from querying field <code>%s</code> when argument <code>%s</code> is not provided. Use <code>%s</code> for unlimited', 'graphql-api');
        $maxLimitMessagePlaceholder = \__('Maximum number of results from querying field <code>%s</code>. Use <code>%s</code> for unlimited', 'graphql-api');
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::MAIN) {
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
                Properties::TITLE => \__('Editing Access', 'graphql-api'),
                Properties::DESCRIPTION => \__('Who has editing access for Persisted Queries, Custom Endpoints and related post types', 'graphql-api'),
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
                // Properties::DEFAULT_VALUE => $this->getSettingsDefaultValue(
                //     $module,
                //     self::OPTION_PATH
                // ),
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
                // Properties::DEFAULT_VALUE => $this->getSettingsDefaultValue(
                //     $module,
                //     self::OPTION_PATH
                // ),
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
                // Properties::DEFAULT_VALUE => $this->getSettingsDefaultValue(
                //     $module,
                //     self::OPTION_PATH
                // ),
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
                // Properties::DEFAULT_VALUE => $this->getSettingsDefaultValue(
                //     $module,
                //     self::OPTION_PATH
                // ),
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
                // Properties::DEFAULT_VALUE => $this->getSettingsDefaultValue(
                //     $module,
                //     self::OPTION_PATH
                // ),
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
                // Properties::DEFAULT_VALUE => self::OPTION_VALUE_NO_VALUE_ID,
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
                // Properties::DEFAULT_VALUE => false,
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
                // Properties::DEFAULT_VALUE => SchemaModes::PUBLIC_SCHEMA_MODE,
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
                // Properties::DEFAULT_VALUE => true,
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
                // Properties::DEFAULT_VALUE => 60,
                Properties::TYPE => Properties::TYPE_INT,
            ];
        } elseif (
            in_array($module, [
                self::SCHEMA_POST_TYPE,
                self::SCHEMA_USER_TYPE,
                self::SCHEMA_TAXONOMY_TYPE,
                self::SCHEMA_PAGE_TYPE,
            ])
        ) {
            $moduleFieldOptions = [
                self::SCHEMA_POST_TYPE => [
                    'posts' => [self::OPTION_POST_DEFAULT_LIMIT, self::OPTION_POST_MAX_LIMIT],
                    'content' => [self::OPTION_CONTENT_DEFAULT_LIMIT, self::OPTION_CONTENT_MAX_LIMIT],
                ],
                self::SCHEMA_USER_TYPE => [
                    'users' => [self::OPTION_USER_DEFAULT_LIMIT, self::OPTION_USER_MAX_LIMIT],
                ],
                self::SCHEMA_TAXONOMY_TYPE => [
                    'tags' => [self::OPTION_TAG_DEFAULT_LIMIT, self::OPTION_TAG_MAX_LIMIT],
                ],
                self::SCHEMA_PAGE_TYPE => [
                    'pages' => [self::OPTION_PAGE_DEFAULT_LIMIT, self::OPTION_PAGE_MAX_LIMIT],
                ],
            ];
            foreach ($moduleFieldOptions[$module] as $field => $options) {
                list(
                    $defaultLimitOption,
                    $maxLimitOption,
                ) = $options;
                $moduleSettings[] = [
                    Properties::INPUT => $defaultLimitOption,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $defaultLimitOption,
                    ),
                    Properties::TITLE => sprintf(
                        \__('Default limit for <code>%s</code>', 'graphql-api'),
                        $field
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $defaultLimitMessagePlaceholder,
                        $field,
                        $limitArg,
                        $unlimitedValue
                    ),
                    Properties::TYPE => Properties::TYPE_INT,
                ];
                $moduleSettings[] = [
                    Properties::INPUT => $maxLimitOption,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $maxLimitOption,
                    ),
                    Properties::TITLE => sprintf(
                        \__('Max limit for <code>%s</code>', 'graphql-api'),
                        $field
                    ),
                    Properties::DESCRIPTION => sprintf(
                        $maxLimitMessagePlaceholder,
                        $field,
                        $unlimitedValue
                    ),
                    Properties::TYPE => Properties::TYPE_INT,
                ];
            }
        }
        return $moduleSettings;
    }

    /**
     * Where the markdown file localized to the user's language is stored
     *
     * @param string $module
     * @return string
     */
    public function getLocalizedMarkdownFileDir(string $module): string
    {
        return $this->getMarkdownFileDir($module, LocaleUtils::getLocaleLanguage());
    }

    /**
     * Where the default markdown file (for if the localized language is not available) is stored
     * Default language for documentation: English
     *
     * @param string $module
     * @return string
     */
    public function getDefaultMarkdownFileDir(string $module): string
    {
        return $this->getMarkdownFileDir(
            $module,
            $this->getDefaultDocumentationLanguage()
        );
    }

    /**
     * Default language for documentation: English
     *
     * @param string $module
     * @return string
     */
    public function getDefaultDocumentationLanguage(): string
    {
        return 'en';
    }

    /**
     * Undocumented function
     *
     * @param string $module
     * @param string $lang
     * @return string
     */
    protected function getMarkdownFileDir(string $module, string $lang): string
    {
        return constant('GRAPHQL_API_DIR') . "/docs/${lang}/modules";
    }

    /**
     * Path URL to append to the local images referenced in the markdown file
     *
     * @param string $module
     * @return string|null
     */
    protected function getDefaultMarkdownFileURL(string $module): string
    {
        $lang = $this->getDefaultDocumentationLanguage();
        return constant('GRAPHQL_API_URL') . "docs/${lang}/modules";
    }

    // /**
    //  * Does the module have HTML Documentation?
    //  *
    //  * @param string $module
    //  * @return bool
    //  */
    // public function hasDocumentation(string $module): bool
    // {
    //     $skipDocumentationModules = [
    //         self::SCHEMA_POST_TYPE,
    //         self::SCHEMA_COMMENT_TYPE,
    //         self::SCHEMA_USER_TYPE,
    //         self::SCHEMA_PAGE_TYPE,
    //         self::SCHEMA_MEDIA_TYPE,
    //         self::SCHEMA_TAXONOMY_TYPE,
    //     ];
    //     if (in_array($module, $skipDocumentationModules)) {
    //         return false;
    //     }
    //     return $this->upstreamHasDocumentation($module);
    // }
}
