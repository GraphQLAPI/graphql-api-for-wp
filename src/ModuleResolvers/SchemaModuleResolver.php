<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use PoP\Pages\TypeResolvers\PageTypeResolver;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\Users\TypeResolvers\UserTypeResolver;
use PoP\Media\TypeResolvers\MediaTypeResolver;
use PoP\Tags\TypeResolvers\TagTypeResolver;
use PoP\Comments\TypeResolvers\CommentTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use PoP\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLCacheControlListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLAccessControlListPostType;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractSchemaModuleResolver;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLEndpointPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use PoP\GenericCustomPosts\TypeResolvers\GenericCustomPostTypeResolver;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLFieldDeprecationListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLPersistedQueryPostType;
use PoP\UsefulDirectives\DirectiveResolvers\LowerCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\TitleCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\UpperCaseStringDirectiveResolver;

class SchemaModuleResolver extends AbstractSchemaModuleResolver
{
    use ModuleResolverTrait;

    public const DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE = Plugin::NAMESPACE . '\directive-set-convert-lower-uppercase';
    public const SCHEMA_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-customposts';
    public const SCHEMA_GENERIC_CUSTOMPOST_TYPE = Plugin::NAMESPACE . '\schema-generic-custompost-type';
    public const SCHEMA_POST_TYPE = Plugin::NAMESPACE . '\schema-post-type';
    public const SCHEMA_COMMENT_TYPE = Plugin::NAMESPACE . '\schema-comment-type';
    public const SCHEMA_USER_TYPE = Plugin::NAMESPACE . '\schema-user-type';
    public const SCHEMA_PAGE_TYPE = Plugin::NAMESPACE . '\schema-page-type';
    public const SCHEMA_MEDIA_TYPE = Plugin::NAMESPACE . '\schema-media-type';
    public const SCHEMA_TAG_TYPE = Plugin::NAMESPACE . '\schema-tag-type';

    /**
     * Setting options
     */
    public const OPTION_LIST_DEFAULT_LIMIT = 'list-default-limit';
    public const OPTION_LIST_MAX_LIMIT = 'list-max-limit';
    public const OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE = 'add-type-to-custompost-union-type';
    public const OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE = 'use-single-type-instead-of-union-type';
    public const OPTION_CUSTOMPOST_TYPES = 'custompost-types';

    /**
     * Hooks
     */
    public const HOOK_GENERIC_CUSTOMPOST_TYPES = __CLASS__ . ':generic-custompost-types';

    public static function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_GENERIC_CUSTOMPOST_TYPE,
            self::SCHEMA_POST_TYPE,
            self::SCHEMA_PAGE_TYPE,
            self::SCHEMA_USER_TYPE,
            self::SCHEMA_COMMENT_TYPE,
            self::SCHEMA_TAG_TYPE,
            self::SCHEMA_MEDIA_TYPE,
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE:
            case self::SCHEMA_USER_TYPE:
            case self::SCHEMA_MEDIA_TYPE:
            case self::SCHEMA_CUSTOMPOSTS:
                return [
                    [
                        FunctionalityModuleResolver::SINGLE_ENDPOINT,
                        FunctionalityModuleResolver::PERSISTED_QUERIES,
                        FunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_GENERIC_CUSTOMPOST_TYPE:
            case self::SCHEMA_POST_TYPE:
            case self::SCHEMA_PAGE_TYPE:
            case self::SCHEMA_COMMENT_TYPE:
            case self::SCHEMA_TAG_TYPE:
                return [
                    [
                        self::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE => \__('Directive Set: Convert Lower/Uppercase', 'graphql-api'),
            self::SCHEMA_GENERIC_CUSTOMPOST_TYPE => \__('Schema Generic Custom Post Type', 'graphql-api'),
            self::SCHEMA_POST_TYPE => \__('Schema Post Type', 'graphql-api'),
            self::SCHEMA_COMMENT_TYPE => \__('Schema Comment Type', 'graphql-api'),
            self::SCHEMA_USER_TYPE => \__('Schema User Type', 'graphql-api'),
            self::SCHEMA_PAGE_TYPE => \__('Schema Page Type', 'graphql-api'),
            self::SCHEMA_MEDIA_TYPE => \__('Schema Media Type', 'graphql-api'),
            self::SCHEMA_TAG_TYPE => \__('Schema Tag Type', 'graphql-api'),
            self::SCHEMA_CUSTOMPOSTS => \__('Schema Custom Posts', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE:
                return sprintf(
                    \__('Set of directives to manipulate strings: <code>@%s</code>, <code>@%s</code> and <code>@%s</code>', 'graphql-api'),
                    UpperCaseStringDirectiveResolver::getDirectiveName(),
                    LowerCaseStringDirectiveResolver::getDirectiveName(),
                    TitleCaseStringDirectiveResolver::getDirectiveName()
                );
            case self::SCHEMA_GENERIC_CUSTOMPOST_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    GenericCustomPostTypeResolver::NAME,
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
            case self::SCHEMA_TAG_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> type to the schema', 'graphql-api'),
                    TagTypeResolver::NAME,
                );
            case self::SCHEMA_CUSTOMPOSTS:
                return \__('Add base functionality for all custom posts', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Indicate if the given value is valid for that option
     *
     * @param string $module
     * @param string $option
     * @param mixed $value
     * @return bool
     */
    public function isValidValue(string $module, string $option, $value): bool
    {
        if (in_array(
            $module,
            [
                self::SCHEMA_CUSTOMPOSTS,
                // self::SCHEMA_GENERIC_CUSTOMPOST_TYPE,
                // self::SCHEMA_POST_TYPE,
                self::SCHEMA_USER_TYPE,
                self::SCHEMA_TAG_TYPE,
                // self::SCHEMA_PAGE_TYPE,
            ]
        ) && in_array(
            $option,
            [
                self::OPTION_LIST_DEFAULT_LIMIT,
                self::OPTION_LIST_MAX_LIMIT,
            ]
        )) {
            // It can't be less than -1, or 0
            if ($value < -1 or $value === 0) {
                return false;
            }
        }
        return parent::isValidValue($module, $option, $value);
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
            self::SCHEMA_CUSTOMPOSTS => [
                self::OPTION_LIST_DEFAULT_LIMIT => 10,
                self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
            ],
            self::SCHEMA_GENERIC_CUSTOMPOST_TYPE => [
                // self::OPTION_LIST_DEFAULT_LIMIT => 10,
                // self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_CUSTOMPOST_TYPES => ['post'],
            ],
            self::SCHEMA_POST_TYPE => [
                // self::OPTION_LIST_DEFAULT_LIMIT => 10,
                // self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE => true,
            ],
            self::SCHEMA_PAGE_TYPE => [
                // self::OPTION_LIST_DEFAULT_LIMIT => 10,
                // self::OPTION_LIST_MAX_LIMIT => 100,
                self::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE => false,
            ],
            self::SCHEMA_USER_TYPE => [
                self::OPTION_LIST_DEFAULT_LIMIT => 10,
                self::OPTION_LIST_MAX_LIMIT => 100,
            ],
            self::SCHEMA_TAG_TYPE => [
                self::OPTION_LIST_DEFAULT_LIMIT => 50,
                self::OPTION_LIST_MAX_LIMIT => 500,
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
        // Common variables to set the limit on the schema types
        $limitArg = 'limit';
        $unlimitedValue = -1;
        $defaultLimitMessagePlaceholder = \__('Number of results from querying %s when argument <code>%s</code> is not provided. Use <code>%s</code> for unlimited', 'graphql-api');
        $maxLimitMessagePlaceholder = \__('Maximum number of results from querying %s. Use <code>%s</code> for unlimited', 'graphql-api');
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if (
            in_array($module, [
                self::SCHEMA_CUSTOMPOSTS,
                // self::SCHEMA_GENERIC_CUSTOMPOST_TYPE,
                // self::SCHEMA_POST_TYPE,
                self::SCHEMA_USER_TYPE,
                self::SCHEMA_TAG_TYPE,
                // self::SCHEMA_PAGE_TYPE,
            ])
        ) {
            $moduleEntries = [
                self::SCHEMA_CUSTOMPOSTS => [
                    'entities' => \__('custom posts', 'graphql-api'),
                ],
                // self::SCHEMA_GENERIC_CUSTOMPOST_TYPE => [
                //     'genericCustomPosts' => null,
                // ],
                // self::SCHEMA_POST_TYPE => [
                //     'posts' => null,
                // ],
                self::SCHEMA_USER_TYPE => [
                    'entities' => \__('users', 'graphql-api'),
                ],
                self::SCHEMA_TAG_TYPE => [
                    'entities' => \__('tags', 'graphql-api'),
                ],
                // self::SCHEMA_PAGE_TYPE => [
                //     'pages' => null,
                // ],
            ];
            $moduleEntry =$moduleEntries[$module];
            // If the options is not provided, use the default one
            $entities = $moduleEntry['entities'];
            $options = $moduleEntry['options'] ?? [
                self::OPTION_LIST_DEFAULT_LIMIT,
                self::OPTION_LIST_MAX_LIMIT,
            ];
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
                    \__('Default limit for %s', 'graphql-api'),
                    $entities
                ),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    $entities,
                    $limitArg,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];
            $moduleSettings[] = [
                Properties::INPUT => $maxLimitOption,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $maxLimitOption,
                ),
                Properties::TITLE => sprintf(
                    \__('Max limit for %s', 'graphql-api'),
                    $entities
                ),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    $entities,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            if ($module == self::SCHEMA_CUSTOMPOSTS) {
                $option = self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option,
                    ),
                    Properties::TITLE => \__('Use single type instead of union type?', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(
                        \__('If type <code>%s</code> is composed of only one type (eg: <code>%s</code>), then return this single type directly in field <code>%s</code>?', 'graphql-api'),
                        CustomPostUnionTypeResolver::NAME,
                        PostTypeResolver::NAME,
                        'customPosts'
                    ),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        } elseif (
            in_array($module, [
                self::SCHEMA_POST_TYPE,
                self::SCHEMA_PAGE_TYPE,
            ])
        ) {
            $titlePlaceholder = sprintf(
                \__('Include type <code>%1$s</code> in <code>%2$s</code>?', 'graphql-api'),
                '%1$s',
                CustomPostUnionTypeResolver::NAME
            );
            $moduleTitles = [
                self::SCHEMA_POST_TYPE => sprintf(
                    $titlePlaceholder,
                    PostTypeResolver::NAME
                ),
                self::SCHEMA_PAGE_TYPE => sprintf(
                    $titlePlaceholder,
                    PageTypeResolver::NAME
                ),
            ];
            $descriptionPlaceholder = sprintf(
                \__('Results of type <code>%1$s</code> will be included when querying a field of type <code>%2$s</code> (such as <code>%3$s</code>)', 'graphql-api'),
                '%1$s',
                CustomPostUnionTypeResolver::NAME,
                'customPosts'
            );
            $moduleDescriptions = [
                self::SCHEMA_POST_TYPE => sprintf(
                    $descriptionPlaceholder,
                    PostTypeResolver::NAME
                ),
                self::SCHEMA_PAGE_TYPE => sprintf(
                    $descriptionPlaceholder,
                    PageTypeResolver::NAME
                ),
            ];
            $option = self::OPTION_ADD_TYPE_TO_CUSTOMPOST_UNION_TYPE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
                ),
                Properties::TITLE => $moduleTitles[$module],
                Properties::DESCRIPTION => $moduleDescriptions[$module],
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module == self::SCHEMA_GENERIC_CUSTOMPOST_TYPE) {
            // Get the list of custom post types from the system
            $genericCustomPostTypes = \get_post_types();
            // Not all custom post types make sense or are allowed.
            // Remove the ones that do not
            $genericCustomPostTypes = array_values(array_diff(
                $genericCustomPostTypes,
                [
                    // Post Types from GraphQL API that contain private data
                    GraphQLAccessControlListPostType::POST_TYPE,
                    GraphQLCacheControlListPostType::POST_TYPE,
                    GraphQLFieldDeprecationListPostType::POST_TYPE,
                    GraphQLSchemaConfigurationPostType::POST_TYPE,
                    GraphQLEndpointPostType::POST_TYPE,
                    GraphQLPersistedQueryPostType::POST_TYPE,
                    // WordPress internal CPTs
                    // Attachment not allowed because its post_status="inherit",
                    // not "publish", and the API filters by "publish" entries
                    'attachment',
                    'revision',
                    'nav_menu_item',
                    'custom_css',
                    'customize_changeset',
                    'oembed_cache',
                    'user_request',
                    'wp_block',
                    'wp_area',
                ]
            ));
            // Allow plugins to remove their own unwanted custom post types
            $genericCustomPostTypes = \apply_filters(
                self::HOOK_GENERIC_CUSTOMPOST_TYPES,
                $genericCustomPostTypes
            );
            // The possible values must have key and value
            $possibleValues = [];
            foreach ($genericCustomPostTypes as $genericCustomPostType) {
                $possibleValues[$genericCustomPostType] = $genericCustomPostType;
            }
            // Set the setting
            $option = self::OPTION_CUSTOMPOST_TYPES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option,
                ),
                Properties::TITLE => \__('Included custom post types', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Results from these custom post types will be included when querying a field with type <code>%s</code> (such as <code>%s</code>)<br/>Press <code>ctrl</code> or <code>shift</code> keys to select more than one', 'graphql-api'),
                    GenericCustomPostTypeResolver::NAME,
                    'genericCustomPosts'
                ),
                Properties::TYPE => Properties::TYPE_ARRAY,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
                Properties::IS_MULTIPLE => true,
            ];
        }

        return $moduleSettings;
    }
}
