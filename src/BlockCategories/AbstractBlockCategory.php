<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\BlockCategories;

abstract class AbstractBlockCategory
{
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
     * Custom Post Type for which to enable the block category
     *
     * @return string
     */
    abstract protected function getPostType(): string;

    /**
     * Block category's slug
     *
     * @return string
     */
    abstract protected function getBlockCategorySlug(): string;

    /**
     * Block category's title
     *
     * @return string
     */
    abstract protected function getBlockCategoryTitle(): string;

    /**
     * Register the category when in the corresponding CPT
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
        if ($post->post_type == $this->getPostType()) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => $this->getBlockCategorySlug(),
                        'title' => $this->getBlockCategoryTitle(),
                    ),
                )
            );
        }

        return $categories;
    }
}
