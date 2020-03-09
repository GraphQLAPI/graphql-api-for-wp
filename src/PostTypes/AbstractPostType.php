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
    protected function getArgs(): array
    {
        return array(
            'label' => $this->getPostTypeName(),
            'labels' => $this->getLabels(),
            'capability_type' => 'post',
            'hierarchical' => false,
            'exclude_from_search' => true,
            'show_in_admin_bar' => false,
            'show_in_menu' => false,
            'show_in_rest' => true,
        );
    }

    /**
     * Labels for registering the post type
     *
     * @return array
     */
    protected function getLabels(): array
    {
        /**
         * Placeholders for printing the different labels
         */
        $placeholder_add_new = \__('Add New %s', 'graphql-by-pop');
        $placeholder_add_new_item = \__('Add New %s', 'graphql-by-pop');
        $placeholder_edit_item = \__('Edit %s', 'graphql-by-pop');
        $placeholder_new_item = \__('New %s', 'graphql-by-pop');
        $placeholder_all_items = \__('All %s', 'graphql-by-pop');
        $placeholder_view_item = \__('View %s', 'graphql-by-pop');
        $placeholder_search_items = \__('Search %s', 'graphql-by-pop');
        $placeholder_not_found = \__('No %s found', 'graphql-by-pop');
        $placeholder_not_found_in_trash = \__('No %s found in Trash', 'graphql-by-pop');
        $placeholder_all_items = \__('All %s', 'graphql-by-pop');

        $name_uc = $this->getPostTypeName();
        $names_uc = $this->getPostTypePluralNames(true);
        $names_lc = $this->getPostTypePluralNames(false);
        return array(
            'name'               => $names_uc,
            'singular_name'      => $name_uc,
            'add_new'            => sprintf($placeholder_add_new, $name_uc),
            'add_new_item'       => sprintf($placeholder_add_new_item, $name_uc),
            'edit_item'          => sprintf($placeholder_edit_item, $name_uc),
            'new_item'           => sprintf($placeholder_new_item, $name_uc),
            'all_items'          => sprintf($placeholder_all_items, $names_uc),
            'view_item'          => sprintf($placeholder_view_item, $name_uc),
            'search_items'       => sprintf($placeholder_search_items, $names_uc),
            'not_found'          => sprintf($placeholder_not_found, $names_lc),
            'not_found_in_trash' => sprintf($placeholder_not_found_in_trash, $names_lc),
            'all_items'          => sprintf($placeholder_all_items, $names_uc),
        );
    }

    /**
     * Initialize the different post types
     *
     * @return void
     */
    public function initPostType(): void
    {
        \register_post_type($this->getPostType(), $this->getArgs());
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
