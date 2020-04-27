<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\BlockCategories;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLEndpointPostType;

/**
 * It comprises the endpoint and the persisted query CPTs
 */
class QueryExecutionBlockCategory extends AbstractBlockCategory
{
    public const QUERY_EXECUTION_BLOCK_CATEGORY = 'graphql-api-query-exec';

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string
     */
    protected function getPostTypes(): array
    {
        return [
            GraphQLQueryPostType::POST_TYPE,
            GraphQLEndpointPostType::POST_TYPE,
        ];
    }

    /**
     * Block category's slug
     *
     * @return string
     */
    protected function getBlockCategorySlug(): string
    {
        return self::QUERY_EXECUTION_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     *
     * @return string
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Query execution (endpoint/persisted query)', 'graphql-api');
    }
}
