<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\Admin\Menu;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;

abstract class AbstractPostType
{
    /**
     * Add the hook to initialize the different post types
     *
     * @return void
     */
    public function init(): void
    {
        \add_action(
            'init',
            [$this, 'initPostType']
        );
        \add_action(
            'init',
            [$this, 'maybeLockGutenbergTemplate']
        );
        \add_filter(
            'allowed_block_types',
            [$this, 'allowGutenbergBlocksForCustomPostType'],
            10,
            2
        );

        /** Add the excerpt, which is the description of the different CPTs (GraphQL query/ACL/CCL) */
        if ($this->usePostExcerptAsDescription()) {
            // Execute last as to always add the description at the top
            \add_filter(
                'the_content',
                [$this, 'maybeAddExcerptAsDescription'],
                PHP_INT_MAX
            );
        }
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
     */
    public function usePostExcerptAsDescription(): bool
    {
        return false;
    }

    /**
     * Block align class
     *
     * @return boolean
     */
    public function getAlignClass(): string
    {
        return 'alignwide';
    }

    /**
     * Render the excerpt as the description for the current CPT
     * Can enable/disable through environment variable
     *
     * @param [type] $content
     * @return string
     */
    public function maybeAddExcerptAsDescription(string $content): string
    {
        /**
         * Check if it is enabled and it is this CPT...
         */
        if (ComponentConfiguration::addExcerptAsDescription() && \is_singular($this->getPostType())) {
            /**
             * Add the excerpt (if not empty) as description of the GraphQL query
             */
            global $post;
            if ($excerpt = $post->post_excerpt) {
                $content = \sprintf(
                    \__('<p class="%s"><strong>Description: </strong>%s</p>'),
                    $this->getAlignClass(),
                    $excerpt
                ) . $content;
            }
        }
        return $content;
    }

    /**
     * Custom Post Type singular name
     *
     * @return string
     */
    abstract protected function getPostTypeName(): string;
    /**
     * Custom Post Type under which it will be registered
     * From documentation: Max. 20 characters and may only contain lowercase alphanumeric characters, dashes, and underscores.
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#Parameters
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return strtolower(str_replace(' ', '-', $this->getPostTypeName()));
    }
    /**
     * Custom Post Type plural name
     *
     * @param boolean $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        $postTypeName = $this->getPostTypeName();
        if ($uppercase) {
            return $postTypeName;
        }
        return strtolower($postTypeName);
    }

    /**
     * Arguments for registering the post type
     *
     * @return array
     */
    protected function getPostTypeArgs(): array
    {
        $name_uc = $this->getPostTypeName();
        $names_uc = $this->getPostTypePluralNames(true);
        $names_lc = $this->getPostTypePluralNames(false);
        $postTypeArgs = array(
            'label' => $this->getPostTypeName(),
            'labels' => $this->getPostTypeLabels($name_uc, $names_uc, $names_lc),
            'capability_type' => 'post',
            'hierarchical' => false,
            'exclude_from_search' => true,
            'show_in_admin_bar' => false,
            'show_in_menu' => Menu::NAME,
            'show_in_rest' => true,
            'public' => true,
            'supports' => [
                'title',
                'editor',
                'author',
                'revisions',
                'custom-fields',
            ],
        );
        if ($this->usePostExcerptAsDescription()) {
            $postTypeArgs['supports'][] = 'excerpt';
        }
        if ($template = $this->getGutenbergTemplate()) {
            $postTypeArgs['template'] = $template;
        }
        return $postTypeArgs;
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array
     */
    protected function getPostTypeLabels(string $name_uc, string $names_uc, string $names_lc): array
    {
        return array(
            'name'               => $names_uc,
            'singular_name'      => $name_uc,
            'add_new'            => sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'add_new_item'       => sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'edit_item'          => sprintf(\__('Edit %s', 'graphql-api'), $name_uc),
            'new_item'           => sprintf(\__('New %s', 'graphql-api'), $name_uc),
            'all_items'          => $names_uc,//sprintf(\__('All %s', 'graphql-api'), $names_uc),
            'view_item'          => sprintf(\__('View %s', 'graphql-api'), $name_uc),
            'search_items'       => sprintf(\__('Search %s', 'graphql-api'), $names_uc),
            'not_found'          => sprintf(\__('No %s found', 'graphql-api'), $names_lc),
            'not_found_in_trash' => sprintf(\__('No %s found in Trash', 'graphql-api'), $names_lc),
            'parent_item_colon'  => sprintf(\__('Parent %s:', 'graphql-api'), $name_uc),
        );
    }

    /**
     * Labels for registering the taxonomy
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $name_lc Singulare name lowercase
     * @param string $names_lc Plural name lowercase
     * @return array
     */
    protected function getTaxonomyLabels(string $name_uc, string $names_uc, string $name_lc, string $names_lc): array
    {
        return array(
            'name'                           => $names_uc,
            'singular_name'                  => $name_uc,
            'menu_name'                      => $names_uc,
            'search_items'                   => \sprintf(\__('Search %s', 'graphql-api'), $names_uc),
            'all_items'                      => $names_uc,//\sprintf(\__('All %s', 'graphql-api'), $names_uc),
            'edit_item'                      => \sprintf(\__('Edit %s', 'graphql-api'), $name_uc),
            'update_item'                    => \sprintf(\__('Update %s', 'graphql-api'), $name_uc),
            'add_new_item'                   => \sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'new_item_name'                  => \sprintf(\__('Add New %s', 'graphql-api'), $name_uc),
            'view_item'                      => \sprintf(\__('View %s', 'graphql-api'), $name_uc),
            'popular_items'                  => \sprintf(\__('Popular %s', 'graphql-api'), $names_lc),
            'separate_items_with_commas'     => \sprintf(\__('Separate %s with commas', 'graphql-api'), $names_lc),
            'add_or_remove_items'            => \sprintf(\__('Add or remove %s', 'graphql-api'), $name_lc),
            'choose_from_most_used'          => \sprintf(\__('Choose from the most used %s', 'graphql-api'), $names_lc),
            'not_found'                      => \sprintf(\__('No %s found', 'graphql-api'), $names_lc),
        );
    }

    /**
     * Initialize the different post types
     *
     * @return void
     */
    public function initPostType(): void
    {
        // First install the taxonomies, if any
        $this->installTaxonomies();
        // Then register the post type
        \register_post_type($this->getPostType(), $this->getPostTypeArgs());
    }

    /**
     * Install the taxonomies, if any
     *
     * @return void
     */
    protected function installTaxonomies(): void
    {
        // By default, nothing to do
    }

    /**
     * Lock down the Custom Post Type to use the given Gutenberg templates
     *
     * @return void
     * @see https://developer.wordpress.org/block-editor/developers/block-api/block-templates/#locking
     */
    public function maybeLockGutenbergTemplate(): void
    {
        if (!empty($this->getGutenbergTemplate()) && $this->lockGutenbergTemplate()) {
            $post_type_object = \get_post_type_object($this->getPostType());
            $post_type_object->template_lock = 'all';
        }
    }

    /**
     * Restrict the Gutenberg blocks available for this Custom Post Type
     *
     * @param [type] $allowedBlocks
     * @param [type] $post
     * @return array
     */
    public function allowGutenbergBlocksForCustomPostType($allowedBlocks, $post)
    {
        /**
         * Check it is this CPT
         */
        if ($post->post_type == $this->getPostType()) {
            if ($blocks = $this->getGutenbergBlocksForCustomPostType()) {
                return $blocks;
            }
        }
        return $allowedBlocks;
    }

    /**
     * Comment: this function below to remove block types doesn't work, because some of the most basic ones, such as "core/paragraph",
     * are never registered using `register_block_types`, then they can't be obtained from `\WP_Block_Type_Registry::get_instance()->get_all_registered()`,
     * and this information exists nowhere.
     *
     * As a consequence, I am currently disabling blocks by assigning them a category (Eg: "Access Control for GraphiQL") which is not registered for other CPTs
     * Unluckily, this produces an error on JavaScript:
     * > The block "graphql-api/access-control" must have a registered category.
     * > The block "graphql-api/access-control-disable-access" must have a registered category.
     * > ...
     *
     * But at least it works
     */
    // /**
    //  * Restrict the Gutenberg blocks available for this Custom Post Type
    //  *
    //  * @param [type] $allowedBlocks
    //  * @param [type] $post
    //  * @return array
    //  */
    // public function allowGutenbergBlocksForCustomPostType($allowedBlocks, $post)
    // {
    //     if ($blocks = $this->getGutenbergBlocksForCustomPostType()) {
    //         /**
    //          * Check if it is this CPT
    //          */
    //         if ($post->post_type == $this->getPostType()) {
    //             return $blocks;
    //         } elseif ($this->removeGutenbergBlocksForOtherPostTypes($post)) {
    //             // Remove this CPT's blocks from other post types.
    //             // $allowedBlocks can be a boolean. In that case, retrieve all blocks types, and substract the blocks
    //             if (!is_array($allowedBlocks)) {
    //                 $blockTypes = \WP_Block_Type_Registry::get_instance()->get_all_registered();
    //                 $allowedBlocks = array_keys($blockTypes);
    //             }
    //             $allowedBlocks = array_values(array_diff(
    //                 $allowedBlocks,
    //                 $blocks
    //             ));
    //         }
    //     }
    //     return $allowedBlocks;
    // }
    // /**
    //  * Indicate if to not allow this CPT's blocks in other Custom Post Types
    //  *
    //  * @param [type] $post
    //  * @return boolean
    //  */
    // protected function removeGutenbergBlocksForOtherPostTypes($post): bool
    // {
    //     return true;
    // }

    /**
     * By default, if providing a template, then restrict the CPT to the blocks involved in the template
     *
     * @param [type] $allowedBlocks
     * @param [type] $post
     * @return array
     */
    protected function getGutenbergBlocksForCustomPostType()
    {
        /**
         * If the CPT defined a template, then maybe restrict to those blocks
         */
        $template = $this->getGutenbergTemplate();
        if (!empty($template) && $this->enableOnlyGutenbergTemplateBlocks()) {
            // Get all the blocks involved in the template
            return array_values(array_unique(array_map(
                function (array $blockConfiguration) {
                    // The block is the first item from the $blockConfiguration
                    return $blockConfiguration[0];
                },
                $template
            )));
        }
        return [];
    }

    /**
     * Indicate if to restrict the blocks for the current post type to those involved in the template
     *
     * @return boolean `true` by default
     */
    protected function enableOnlyGutenbergTemplateBlocks(): bool
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
        return [];
    }

    /**
     * Indicates if to lock the Gutenberg templates
     *
     * @return boolean
     */
    protected function lockGutenbergTemplate(): bool
    {
        return false;
    }
}
