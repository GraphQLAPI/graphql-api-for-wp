<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

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
            [$this, 'maybeSetGutenbergTemplates']
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
     * Render the excerpt as the description for the current CPT
     *
     * @param [type] $content
     * @return string
     */
    public function maybeAddExcerptAsDescription($content): string
    {
        /**
         * Check if it is this CPT...
         */
        if (\is_singular($this->getPostType())) {
            /**
             * Add the excerpt (if not empty) as description of the GraphQL query
             */
            global $post;
            if ($excerpt = $post->post_excerpt) {
                $content = \sprintf(
                    \__('<p><strong>Description: </strong>%s</p>'),
                    $excerpt
                ).$content;
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
            'show_in_admin_bar' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'public' => true,
            'supports' => [
                'title',
                'editor',
                'author',
                'revisions',
            ],
        );
        if ($this->usePostExcerptAsDescription()) {
            $postTypeArgs['supports'][] = 'excerpt';
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
            'add_new'            => sprintf(\__('Add New %s', 'graphql-by-pop'), $name_uc),
            'add_new_item'       => sprintf(\__('Add New %s', 'graphql-by-pop'), $name_uc),
            'edit_item'          => sprintf(\__('Edit %s', 'graphql-by-pop'), $name_uc),
            'new_item'           => sprintf(\__('New %s', 'graphql-by-pop'), $name_uc),
            'all_items'          => sprintf(\__('All %s', 'graphql-by-pop'), $names_uc),
            'view_item'          => sprintf(\__('View %s', 'graphql-by-pop'), $name_uc),
            'search_items'       => sprintf(\__('Search %s', 'graphql-by-pop'), $names_uc),
            'not_found'          => sprintf(\__('No %s found', 'graphql-by-pop'), $names_lc),
            'not_found_in_trash' => sprintf(\__('No %s found in Trash', 'graphql-by-pop'), $names_lc),
            'all_items'          => sprintf(\__('All %s', 'graphql-by-pop'), $names_uc),
            'parent_item_colon'  => sprintf(\__('Parent %s:', 'graphql-by-pop'), $name_uc),
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
            'search_items'                   => \sprintf(\__('Search %s', 'graphql-by-pop'), $names_uc),
            'all_items'                      => \sprintf(\__('All %s', 'graphql-by-pop'), $names_uc),
            'edit_item'                      => \sprintf(\__('Edit %s', 'graphql-by-pop'), $name_uc),
            'update_item'                    => \sprintf(\__('Update %s', 'graphql-by-pop'), $name_uc),
            'add_new_item'                   => \sprintf(\__('Add New %s', 'graphql-by-pop'), $name_uc),
            'new_item_name'                  => \sprintf(\__('Add New %s', 'graphql-by-pop'), $name_uc),
            'view_item'                      => \sprintf(\__('View %s', 'graphql-by-pop'), $name_uc),
            'popular_items'                  => \sprintf(\__('Popular %s', 'graphql-by-pop'), $names_lc),
            'separate_items_with_commas'     => \sprintf(\__('Separate %s with commas', 'graphql-by-pop'), $names_lc),
            'add_or_remove_items'            => \sprintf(\__('Add or remove %s', 'graphql-by-pop'), $name_lc),
            'choose_from_most_used'          => \sprintf(\__('Choose from the most used %s', 'graphql-by-pop'), $names_lc),
            'not_found'                      => \sprintf(\__('No %s found', 'graphql-by-pop'), $names_lc),
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
    public function maybeSetGutenbergTemplates(): void
    {
        if ($templates = $this->getGutenbergFixedTemplates()) {
            $post_type_object = \get_post_type_object($this->getPostType());
            $post_type_object->template = $templates;
            $post_type_object->template_lock = 'all';
        }
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergFixedTemplates(): array
    {
        return [];
    }
}
