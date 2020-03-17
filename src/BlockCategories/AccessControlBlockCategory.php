<?php
namespace Leoloso\GraphQLByPoPWPPlugin\BlockCategories;

class AccessControlBlockCategory {

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
                    'slug' => 'graphql-api-access-control',
                    'title' => __( 'Access Control for GraphQL', 'graphql-api' ),
                ),
            )
        );
    }
}
