<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;

class GraphQLCacheControlListPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-ccl';

    /**
     * Custom Post Type name
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Custom post type name
     *
     * @return void
     */
    public function getPostTypeName(): string
    {
        return \__('Cache Control List', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param boolean $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('Cache Control Lists', 'graphql-api');
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergTemplate(): array
    {
        // $graphiQLBlock = PluginState::getGraphiQLBlock();
        return [
            // [$graphiQLBlock->getBlockFullName()],
        ];
    }
}
