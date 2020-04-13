<?php
namespace Leoloso\GraphQLByPoPWPPlugin\BlockCategories;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;

class AccessControlBlockCategory
{
    public const ACCESS_CONTROL_BLOCK_CATEGORY = 'graphql-api-access-control';

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
     * Register the "Access Control for GraphQL" category when in the Access Control CPT
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
        if ($post->post_type == GraphQLAccessControlListPostType::POST_TYPE) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => self::ACCESS_CONTROL_BLOCK_CATEGORY,
                        'title' => __('Access Control for GraphQL', 'graphql-api'),
                    ),
                )
            );
        }

        return $categories;
    }
}
