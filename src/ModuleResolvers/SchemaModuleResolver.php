<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use PoP\Pages\TypeResolvers\PageTypeResolver;
use PoP\Posts\TypeResolvers\PostTypeResolver;
use PoP\Users\TypeResolvers\UserTypeResolver;
use PoP\Media\TypeResolvers\MediaTypeResolver;
use PoP\Taxonomies\TypeResolvers\TagTypeResolver;
use PoP\Comments\TypeResolvers\CommentTypeResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use PoP\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\CustomPosts\TypeResolvers\CustomPostTypeResolver;
use PoP\UsefulDirectives\DirectiveResolvers\LowerCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\TitleCaseStringDirectiveResolver;
use PoP\UsefulDirectives\DirectiveResolvers\UpperCaseStringDirectiveResolver;

class SchemaModuleResolver extends AbstractSchemaModuleResolver
{
    use ModuleResolverTrait;

    public const DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE = Plugin::NAMESPACE . '\directive-set-convert-lower-uppercase';
    public const SCHEMA_POST_TYPE = Plugin::NAMESPACE . '\schema-post-type';
    public const SCHEMA_COMMENT_TYPE = Plugin::NAMESPACE . '\schema-comment-type';
    public const SCHEMA_USER_TYPE = Plugin::NAMESPACE . '\schema-user-type';
    public const SCHEMA_PAGE_TYPE = Plugin::NAMESPACE . '\schema-page-type';
    public const SCHEMA_MEDIA_TYPE = Plugin::NAMESPACE . '\schema-media-type';
    public const SCHEMA_TAXONOMY_TYPE = Plugin::NAMESPACE . '\schema-taxonomy-type';
    public const SCHEMA_CUSTOMPOST_TYPE = Plugin::NAMESPACE . '\schema-custompost-type';

    /**
     * Setting options
     */
    public const OPTION_POST_DEFAULT_LIMIT = 'post-default-limit';
    public const OPTION_POST_MAX_LIMIT = 'post-max-limit';
    public const OPTION_CUSTOMPOST_DEFAULT_LIMIT = 'custompost-default-limit';
    public const OPTION_CUSTOMPOST_MAX_LIMIT = 'custompost-max-limit';
    public const OPTION_USER_DEFAULT_LIMIT = 'user-default-limit';
    public const OPTION_USER_MAX_LIMIT = 'user-max-limit';
    public const OPTION_TAG_DEFAULT_LIMIT = 'tag-default-limit';
    public const OPTION_TAG_MAX_LIMIT = 'tag-max-limit';
    public const OPTION_PAGE_DEFAULT_LIMIT = 'page-default-limit';
    public const OPTION_PAGE_MAX_LIMIT = 'page-max-limit';

    public const OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE = 'use-single-type-instead-of-union-type';

    public static function getModulesToResolve(): array
    {
        return [
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE,
            self::SCHEMA_CUSTOMPOST_TYPE,
            self::SCHEMA_POST_TYPE,
            self::SCHEMA_PAGE_TYPE,
            self::SCHEMA_USER_TYPE,
            self::SCHEMA_COMMENT_TYPE,
            self::SCHEMA_TAXONOMY_TYPE,
            self::SCHEMA_MEDIA_TYPE,
        ];
    }

    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE:
            case self::SCHEMA_USER_TYPE:
            case self::SCHEMA_MEDIA_TYPE:
            case self::SCHEMA_CUSTOMPOST_TYPE:
                return [
                    [
                        FunctionalityModuleResolver::SINGLE_ENDPOINT,
                        FunctionalityModuleResolver::PERSISTED_QUERIES,
                        FunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_POST_TYPE:
            case self::SCHEMA_PAGE_TYPE:
            case self::SCHEMA_COMMENT_TYPE:
            case self::SCHEMA_TAXONOMY_TYPE:
                return [
                    [
                        self::SCHEMA_CUSTOMPOST_TYPE,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE => \__('Directive Set: Convert Lower/Uppercase', 'graphql-api'),
            self::SCHEMA_POST_TYPE => \__('Schema Post Type', 'graphql-api'),
            self::SCHEMA_COMMENT_TYPE => \__('Schema Comment Type', 'graphql-api'),
            self::SCHEMA_USER_TYPE => \__('Schema User Type', 'graphql-api'),
            self::SCHEMA_PAGE_TYPE => \__('Schema Page Type', 'graphql-api'),
            self::SCHEMA_MEDIA_TYPE => \__('Schema Media Type', 'graphql-api'),
            self::SCHEMA_TAXONOMY_TYPE => \__('Schema Taxonomy Type', 'graphql-api'),
            self::SCHEMA_CUSTOMPOST_TYPE => \__('Schema Custom Post Types', 'graphql-api'),
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
            case self::SCHEMA_CUSTOMPOST_TYPE:
                return sprintf(
                    \__('Add the <code>%s</code> and <code>%s</code> types to the schema', 'graphql-api'),
                    CustomPostTypeResolver::NAME,
                    CustomPostUnionTypeResolver::NAME
                );
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
            self::SCHEMA_POST_TYPE => [
                self::OPTION_POST_DEFAULT_LIMIT => 10,
                self::OPTION_POST_MAX_LIMIT => 100,
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
            self::SCHEMA_CUSTOMPOST_TYPE => [
                self::OPTION_CUSTOMPOST_DEFAULT_LIMIT => 10,
                self::OPTION_CUSTOMPOST_MAX_LIMIT => 100,
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
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
        $defaultLimitMessagePlaceholder = \__('Number of results from querying field <code>%s</code> when argument <code>%s</code> is not provided. Use <code>%s</code> for unlimited', 'graphql-api');
        $maxLimitMessagePlaceholder = \__('Maximum number of results from querying field <code>%s</code>. Use <code>%s</code> for unlimited', 'graphql-api');
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if (
            in_array($module, [
                self::SCHEMA_POST_TYPE,
                self::SCHEMA_USER_TYPE,
                self::SCHEMA_TAXONOMY_TYPE,
                self::SCHEMA_PAGE_TYPE,
                self::SCHEMA_CUSTOMPOST_TYPE,
            ])
        ) {
            $moduleFieldOptions = [
                self::SCHEMA_POST_TYPE => [
                    'posts' => [self::OPTION_POST_DEFAULT_LIMIT, self::OPTION_POST_MAX_LIMIT],
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
                self::SCHEMA_CUSTOMPOST_TYPE => [
                    'customPosts' => [self::OPTION_CUSTOMPOST_DEFAULT_LIMIT, self::OPTION_CUSTOMPOST_MAX_LIMIT],
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

            if ($module == self::SCHEMA_CUSTOMPOST_TYPE) {
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
        }
        return $moduleSettings;
    }
}
