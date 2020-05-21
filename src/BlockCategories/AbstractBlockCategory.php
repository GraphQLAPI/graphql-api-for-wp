<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\BlockCategories;

abstract class AbstractBlockCategory
{
    public function initialize(): void
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
    public function getPostTypes(): array
    {
        return [];
    }

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
         * If specified CPTs, register the category only for them
         */
        if (empty($this->getPostTypes()) || in_array($post->post_type, $this->getPostTypes())) {
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
