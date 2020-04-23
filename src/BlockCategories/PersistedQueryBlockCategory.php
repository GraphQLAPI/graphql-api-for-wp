<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\BlockCategories;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;

class PersistedQueryBlockCategory extends AbstractBlockCategory
{
    public const PERSISTED_QUERY_BLOCK_CATEGORY = 'graphql-api-persisted-query';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return GraphQLQueryPostType::POST_TYPE;
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    protected function getBlockCategorySlug(): string
    {
        return self::PERSISTED_QUERY_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     *
     * @return string
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('GraphQL persisted query', 'graphql-api');
    }
}
