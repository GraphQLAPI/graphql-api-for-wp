<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\SettingsMenuPage;

/**
 * Admin menu class
 */
class Menu {
    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function init(): void
    {
        add_action(
            'admin_menu',
            [$this, 'addMenuPages']
        );
    }
    function addMenuPages(): void
    {
        add_menu_page(
            __('GraphQL by PoP', 'graphql-by-pop'),
            __('GraphQL by PoP', 'graphql-by-pop'),
            'manage_options',
            'graphql_by_pop',
            '',
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0MDAgNDAwIj48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNNTcuNDY4IDMwMi42NmwtMTQuMzc2LTguMyAxNjAuMTUtMjc3LjM4IDE0LjM3NiA4LjN6Ii8+PHBhdGggZmlsbD0iI0UxMDA5OCIgZD0iTTM5LjggMjcyLjJoMzIwLjN2MTYuNkgzOS44eiIvPjxwYXRoIGZpbGw9IiNFMTAwOTgiIGQ9Ik0yMDYuMzQ4IDM3NC4wMjZsLTE2MC4yMS05Mi41IDguMy0xNC4zNzYgMTYwLjIxIDkyLjV6TTM0NS41MjIgMTMyLjk0N2wtMTYwLjIxLTkyLjUgOC4zLTE0LjM3NiAxNjAuMjEgOTIuNXoiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNNTQuNDgyIDEzMi44ODNsLTguMy0xNC4zNzUgMTYwLjIxLTkyLjUgOC4zIDE0LjM3NnoiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNMzQyLjU2OCAzMDIuNjYzbC0xNjAuMTUtMjc3LjM4IDE0LjM3Ni04LjMgMTYwLjE1IDI3Ny4zOHpNNTIuNSAxMDcuNWgxNi42djE4NUg1Mi41ek0zMzAuOSAxMDcuNWgxNi42djE4NWgtMTYuNnoiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNMjAzLjUyMiAzNjdsLTcuMjUtMTIuNTU4IDEzOS4zNC04MC40NSA3LjI1IDEyLjU1N3oiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNMzY5LjUgMjk3LjljLTkuNiAxNi43LTMxIDIyLjQtNDcuNyAxMi44LTE2LjctOS42LTIyLjQtMzEtMTIuOC00Ny43IDkuNi0xNi43IDMxLTIyLjQgNDcuNy0xMi44IDE2LjggOS43IDIyLjUgMzEgMTIuOCA0Ny43TTkwLjkgMTM3Yy05LjYgMTYuNy0zMSAyMi40LTQ3LjcgMTIuOC0xNi43LTkuNi0yMi40LTMxLTEyLjgtNDcuNyA5LjYtMTYuNyAzMS0yMi40IDQ3LjctMTIuOCAxNi43IDkuNyAyMi40IDMxIDEyLjggNDcuN00zMC41IDI5Ny45Yy05LjYtMTYuNy0zLjktMzggMTIuOC00Ny43IDE2LjctOS42IDM4LTMuOSA0Ny43IDEyLjggOS42IDE2LjcgMy45IDM4LTEyLjggNDcuNy0xNi44IDkuNi0zOC4xIDMuOS00Ny43LTEyLjhNMzA5LjEgMTM3Yy05LjYtMTYuNy0zLjktMzggMTIuOC00Ny43IDE2LjctOS42IDM4LTMuOSA0Ny43IDEyLjggOS42IDE2LjcgMy45IDM4LTEyLjggNDcuNy0xNi43IDkuNi0zOC4xIDMuOS00Ny43LTEyLjhNMjAwIDM5NS44Yy0xOS4zIDAtMzQuOS0xNS42LTM0LjktMzQuOSAwLTE5LjMgMTUuNi0zNC45IDM0LjktMzQuOSAxOS4zIDAgMzQuOSAxNS42IDM0LjkgMzQuOSAwIDE5LjItMTUuNiAzNC45LTM0LjkgMzQuOU0yMDAgNzRjLTE5LjMgMC0zNC45LTE1LjYtMzQuOS0zNC45IDAtMTkuMyAxNS42LTM0LjkgMzQuOS0zNC45IDE5LjMgMCAzNC45IDE1LjYgMzQuOSAzNC45IDAgMTkuMy0xNS42IDM0LjktMzQuOSAzNC45Ii8+PC9zdmc+'
        );

        add_submenu_page(
            'graphql_by_pop',
            __('GraphiQL', 'graphql-by-pop'),
            __('GraphiQL', 'graphql-by-pop'),
            'manage_options',
            'graphql_by_pop',
            [$this, 'printGraphiQLPage']
        );

        add_submenu_page(
            'graphql_by_pop',
            __('Interactive schema', 'graphql-by-pop'),
            __('Interactive schema', 'graphql-by-pop'),
            'manage_options',
            'graphql_by_pop_voyager',
            [$this, 'printVoyagerPage']
        );

        add_submenu_page(
            'graphql_by_pop',
            __('Settings', 'graphql-by-pop'),
            __('Settings', 'graphql-by-pop'),
            'manage_options',
            'graphql_by_pop_settings',
            [new SettingsMenuPage(), 'print']
        );

        add_submenu_page(
            'graphql_by_pop',
            __('Documentation', 'graphql-by-pop'),
            __('Documentation', 'graphql-by-pop'),
            'manage_options',
            'graphql_by_pop_documentation',
            [$this, 'printVoyagerPage']
        );

        // if (current_user_can('manage_options')) {
        //     global $submenu;
        //     $submenu['graphql_by_pop'][] = [
        //         __('Documentation', 'graphql-by-pop'),
        //         'manage_options',
        //         'https://github.com/getpop/graphql',
        //     ];
        // }
    }

    function printGraphiQLPage() {
        if (!current_user_can('manage_options'))  {
            wp_die(__( 'You do not have sufficient permissions to access this page.'));
        }
        echo 'GraphiQL!';
    }

    function printVoyagerPage() {
        if (!current_user_can('manage_options'))  {
            wp_die(__( 'You do not have sufficient permissions to access this page.'));
        }
        echo 'Voyager!';
    }

    function printOptionsPage() {
        if (!current_user_can('manage_options'))  {
            wp_die(__( 'You do not have sufficient permissions to access this page.'));
        }
        echo '<div class="wrap">';
        echo '<p>Here is where the form would go if I actually had options.</p>';
        echo '</div>';
    }
}
