<?php
namespace Leoloso\GraphQLByPoPWPPlugin\General;

class PostTypes
{
    public const GRAPHQL_QUERY = 'graphql-query';
    /**
     * Add the hook to initialize the different post types
     *
     * @return void
     */
    public function init(): void
    {
        \add_action(
            'init',
            [$this, 'initPostTypes']
        );
        \add_action(
            'init',
            [$this, 'setGutenbergTemplates']
        );
    }

    /**
     * Initialize the different post types
     *
     * @return void
     */
    public function initPostTypes(): void
    {
        /**
         * Args for all custom post types
         */
        $commonArgs = array(
            'public' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'exclude_from_search' => true,
            'show_in_admin_bar' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
        );

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

        /**
         * Register the "graphql-query" post type
         */
        $name_uc = __('GraphQL query', 'graphql-by-pop');
        $names_uc = __('GraphQL queries', 'graphql-by-pop');
        $names_lc = __('GraphQL queries', 'graphql-by-pop');
        $labels = array(
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

        // The arguments for our post type, to be entered as parameter 2 of register_post_type()
        $args = array_merge(
            $commonArgs,
            array(
                'labels' => $labels,
            )
        );
        \register_post_type(self::GRAPHQL_QUERY, $args);
    }

    /**
     * Undocumented function
     *
     * @return void
     * @see https://developer.wordpress.org/block-editor/developers/block-api/block-templates/#locking
     */
    public function setGutenbergTemplates(): void
    {
        $post_type_object = \get_post_type_object( self::GRAPHQL_QUERY );
        $post_type_object->template = array(
            array( 'graphql-by-pop/graphiql', array(
            ) ),
        );
        $post_type_object->template_lock = 'all';
    }
}
