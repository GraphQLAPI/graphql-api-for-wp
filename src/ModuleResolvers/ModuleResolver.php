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
use GraphQLAPI\GraphQLAPI\ModuleSettings\Tokens;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use PoP\Taxonomies\TypeResolvers\TagTypeResolver;
use PoP\Comments\TypeResolvers\CommentTypeResolver;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleSettings\ModuleSettings;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use PoP\UsefulDirectives\DirectiveResolvers\LowerCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\TitleCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\UpperCaseStringDirectiveResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

class ModuleResolver extends AbstractModuleResolver
{
    use HasMarkdownDocumentationModuleResolverTrait;

    // use HasMarkdownDocumentationModuleResolverTrait {
    //     HasMarkdownDocumentationModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    // }

    // public const MAIN = Plugin::NAMESPACE . '\main';
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
            self::PUBLIC_PRIVATE_SCHEMA,
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            self::ACCESS_CONTROL_RULE_USER_STATE,
            self::ACCESS_CONTROL_RULE_USER_ROLES,
            self::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
            self::CACHE_CONTROL,
            self::FIELD_DEPRECATION,
            self::LOW_LEVEL_QUERY_EDITING,
            self::SCHEMA_CACHE,
            self::GRAPHIQL_EXPLORER,
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
                return [
                    [
                        self::PERSISTED_QUERIES,
                        self::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_NAMESPACING:
            case self::PUBLIC_PRIVATE_SCHEMA:
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
            // case self::MAIN:
            case self::SCHEMA_CACHE:
                return true;
        }
        return parent::isHidden($module);
    }

    public function getName(string $module): string
    {
        $names = [
            // self::MAIN => \__('Main', 'graphql-api'),
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
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        switch ($module) {
            // case self::MAIN:
            //      return \__('Main functionality module, can\'t be disabled but is required for defining the main settings', 'graphql-api');
            case self::SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Expose a single GraphQL endpoint under <code>%s</code>, with unrestricted access', 'graphql-api'),
                    $userSettingsManager->getSetting(
                        ModuleSettings::SINGLE_ENDPOINT_SLUG,
                        $this->getSettingsDefaultValue($module)
                    )
                );
            case self::PERSISTED_QUERIES:
                return \__('Expose predefined responses through a custom URL, akin to using GraphQL queries to publish REST endpoints', 'graphql-api');
            case self::CUSTOM_ENDPOINTS:
                return \__('Expose different subsets of the schema for different targets, such as users (clients, employees, etc), applications (website, mobile app, etc), context (weekday, weekend, etc), and others', 'graphql-api');
            case self::GRAPHIQL_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public GraphiQL client available under <code>%s</code>, to execute queries against the single endpoint', 'graphql-api'),
                    $userSettingsManager->getSetting(
                        ModuleSettings::GRAPHIQL_FOR_SINGLE_ENDPOINT_SLUG,
                        $this->getSettingsDefaultValue($module)
                    )
                );
            case self::GRAPHIQL_FOR_CUSTOM_ENDPOINTS:
                return \__('Enable custom endpoints to be attached their own GraphiQL client, to execute queries against them', 'graphql-api');
            case self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT:
                return \sprintf(
                    \__('Make a public Interactive Schema client available under <code>%s</code>, to visualize the schema accessible through the single endpoint', 'graphql-api'),
                    $userSettingsManager->getSetting(
                        ModuleSettings::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT_SLUG,
                        $this->getSettingsDefaultValue($module)
                    )
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
     * Default value for the module
     *
     * @param string $module
     * @return void
     */
    protected function getSettingsDefaultValue(string $module)
    {
        $defaultValues = [
            self::SINGLE_ENDPOINT => '/graphql/',
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT => '/graphiql/',
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT => '/schema/',
        ];
        return $defaultValues[$module];
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @param string $module
     * @return array
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = [];
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::SINGLE_ENDPOINT) {
            $moduleSettings[] = [
                Tokens::NAME => ModuleSettings::SINGLE_ENDPOINT_SLUG,
                Tokens::TITLE => \__('Endpoint slug', 'graphql-api'),
                Tokens::DESCRIPTION => \__('URL slug to expose the single GraphQL endpoint', 'graphql-api'),
                Tokens::DEFAULT_VALUE => $this->getSettingsDefaultValue($module),
                Tokens::TYPE => Tokens::TYPE_STRING,
            ];
        } elseif ($module == self::GRAPHIQL_FOR_SINGLE_ENDPOINT) {
            $moduleSettings[] = [
                Tokens::NAME => ModuleSettings::GRAPHIQL_FOR_SINGLE_ENDPOINT_SLUG,
                Tokens::TITLE => \__('Client slug', 'graphql-api'),
                Tokens::DESCRIPTION => \__('URL slug to access the public GraphiQL client', 'graphql-api'),
                Tokens::DEFAULT_VALUE => $this->getSettingsDefaultValue($module),
                Tokens::TYPE => Tokens::TYPE_STRING,
            ];
        } elseif ($module == self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT) {
            $moduleSettings[] = [
                Tokens::NAME => ModuleSettings::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT_SLUG,
                Tokens::TITLE => \__('Client slug', 'graphql-api'),
                Tokens::DESCRIPTION => \__('URL slug to access the public Interactive Schema client', 'graphql-api'),
                Tokens::DEFAULT_VALUE => $this->getSettingsDefaultValue($module),
                Tokens::TYPE => Tokens::TYPE_STRING,
            ];
        } elseif ($module == self::SCHEMA_CONFIGURATION) {
            $moduleRegistry = ModuleRegistryFacade::getInstance();
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
            $noValueID = 0;
            $possibleValues = [
                $noValueID => \__('None', 'graphql-api'),
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
            $moduleSettings[] = [
                Tokens::NAME => ModuleSettings::SCHEMA_CONFIGURATION_DEFAULT_SCHEMA_CONFIGURATION,
                Tokens::TITLE => \__('Default Schema Configuration', 'graphql-api'),
                Tokens::DESCRIPTION => sprintf(
                    \__('Schema Configuration to use when option <code>"Default"</code> is selected (in %s)', 'graphql-api'),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModules
                    )
                ),
                Tokens::DEFAULT_VALUE => $noValueID,
                Tokens::TYPE => Tokens::TYPE_INT,
                // Fetch all Schema Configurations from the DB
                Tokens::POSSIBLE_VALUES => $possibleValues,
            ];
        } elseif ($module == self::SCHEMA_NAMESPACING) {
            $moduleSettings[] = [
                Tokens::NAME => ModuleSettings::SCHEMA_NAMESPACING_USE_NAMESPACING,
                Tokens::TITLE => \__('Use namespacing?', 'graphql-api'),
                Tokens::DESCRIPTION => \__('Automatically namespace types and interfaces in the schema', 'graphql-api'),
                Tokens::DEFAULT_VALUE => false,
                Tokens::TYPE => Tokens::TYPE_BOOL,
            ];
        } elseif ($module == self::PUBLIC_PRIVATE_SCHEMA) {
            $moduleSettings[] = [
                Tokens::NAME => ModuleSettings::PUBLIC_PRIVATE_SCHEMA_DEFAULT_MODE,
                Tokens::TITLE => \__('Default visibility', 'graphql-api'),
                Tokens::DESCRIPTION => sprintf(
                    \__('Visibility to use for fields and directives in the schema when option <code>"%s"</code> is selected (in ▹ Schema Configuration)', 'graphql-api'),
                    ComponentConfiguration::getSettingsValueLabel()
                ),
                Tokens::DEFAULT_VALUE => SchemaModes::PUBLIC_SCHEMA_MODE,
                Tokens::TYPE => Tokens::TYPE_STRING,
                Tokens::POSSIBLE_VALUES => [
                    SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
                    SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
                ],
            ];
        } elseif ($module == self::CACHE_CONTROL) {
            $moduleSettings[] = [
                Tokens::NAME => ModuleSettings::CACHE_CONTROL_DEFAULT_MAX_AGE,
                Tokens::TITLE => \__('Default max-age', 'graphql-api'),
                Tokens::DESCRIPTION => \__('Default max-age value (in seconds) for the Cache-Control header, for all fields and directives in the schema', 'graphql-api'),
                Tokens::DEFAULT_VALUE => 60,
                Tokens::TYPE => Tokens::TYPE_INT,
            ];
        }
        return array_merge(
            parent::getSettings($module),
            $moduleSettings
        );
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
