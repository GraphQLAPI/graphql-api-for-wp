<?php
namespace Leoloso\GraphQLByPoPWPPlugin\BlockCategories;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLCacheControlListPostType;

class CacheControlBlockCategory
{
    public const CACHE_CONTROL_BLOCK_CATEGORY = 'graphql-api-cache-control';

    public function init()
    {
        \add_filter(
            'block_categories',
            [$this, 'getBlockCategories'],
            10,
            2
        );
    }

    /**
     * Register the "Cache Control for GraphQL" category when in the Cache Control CPT
     *
     * @param [type] $categories
     * @param [type] $post
     * @return void
     */
    public function getBlockCategories(array $categories, $post)
    {
        /**
         * Only register for the Access Control CPT
         */
        if ($post->post_type == GraphQLCacheControlListPostType::POST_TYPE) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => self::CACHE_CONTROL_BLOCK_CATEGORY,
                        'title' => __('Cache Control for GraphQL', 'graphql-api'),
                    ),
                )
            );
        }

        return $categories;
    }
}
