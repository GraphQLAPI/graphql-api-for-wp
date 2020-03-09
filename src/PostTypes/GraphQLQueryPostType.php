<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;

class GraphQLQueryPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const NAME = 'graphql-query';

    /**
     * Custom Post Type name
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return self::NAME;
    }

    /**
     * Custom Post Type singular name
     *
     * @return string
     */
    protected function getPostTypeName(): string
    {
        return \__('GraphQL query', 'graphql-by-pop');
    }

    /**
     * Custom Post Type plural name
     *
     * @param boolean $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL queries', 'graphql-by-pop');
    }

    /**
     * Arguments for registering the post type
     *
     * @return array
     */
    protected function getArgs(): array
    {
        return array_merge(
            parent::getArgs(),
            [
                'public' => 'true',
                'show_in_menu' => true,
                'show_in_admin_bar' => true,
            ]
        );
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergFixedTemplates(): array
    {
        return [
            ['graphql-by-pop/graphiql'],
        ];
    }
}
