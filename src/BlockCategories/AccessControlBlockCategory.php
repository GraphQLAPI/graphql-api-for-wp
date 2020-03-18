<?php
namespace Leoloso\GraphQLByPoPWPPlugin\BlockCategories;

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

    public function getBlockCategories( $categories, $post ) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => self::ACCESS_CONTROL_BLOCK_CATEGORY,
                    'title' => __( 'Access Control for GraphQL', 'graphql-api' ),
                ),
            )
        );
    }
}
