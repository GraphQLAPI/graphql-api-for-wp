<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\Tables;

/**
 * Module Table
 */
class ModuleListTable extends AbstractItemListTable
{
    /**
     * Singular name of the listed records
     *
     * @return string
     */
    public function getItemSingularName(): string
    {
        return \__('Module', 'graphql-api');
    }

    /**
     * Plural name of the listed records
     *
     * @return string
     */
    public function getItemPluralName(): string
    {
        return \__('Modules', 'graphql-api');
    }

    public function getAllItems(): array
    {
        return [
            [
                'id' => 'single-endpoint',
                'enabled' => false,
                'has-settings' => true,
                'name' => \__('Single Endpoint', 'graphql-api'),
                'description' => \sprintf(
                    \__('Make data queryable through a single GraphQL endpoint under <code>%s</code>, with unrestricted access', 'graphql-api'),
                    '/graphql/'
                ),
            ],
            [
                'id' => 'persisted-queries',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Persisted Queries', 'graphql-api'),
                'description' => \__('Expose a predefined response by publishing persisted GraphQL queries, and accessing them under their permalink', 'graphql-api'),
            ],
            [
                'id' => 'custom-endpoints',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Custom Endpoints', 'graphql-api'),
                'description' => \__('Make data queryable through custom endpoints, each accepting a different configuration (access control, cache control, etc)', 'graphql-api'),
            ],
            [
                'id' => 'graphiql-for-custom-endpoints',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('GraphiQL for Custom Endpoints', 'graphql-api'),
                'description' => \__('Enable custom endpoints to be attached a GraphiQL client, to execute queries against them. It depends on module "Custom Endpoints"', 'graphql-api'),
            ],
            [
                'id' => 'interactive-schema-for-custom-endpoints',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Interactive Schema for Custom Endpoints', 'graphql-api'),
                'description' => \__('Enable custom endpoints to be attached an Interactive schema client, to visualize the schema from the custom endpoint after applying all the access control rules. It depends on module "Custom Endpoints"', 'graphql-api'),
            ],
            [
                'id' => 'access-control',
                'enabled' => true,
                'has-settings' => false,
                'name' => \__('Access Control', 'graphql-api'),
                'description' => \__('Set-up rules to define who can access the different fields and directives from a schema', 'graphql-api'),
            ],
            // ['id' => 'access-control---remove-access',
            //     'enabled' => true,
            //     'has-settings' => true,
            //     'name' => \__('Access Control - Remove Access', 'graphql-api'),
            //     'description' => \__('So, here I tell you about Access Control - Remove Access, oh yeah you know', 'graphql-api'),
            // ],
            [
                'id' => 'access-control-rule-user-state',
                'enabled' => true,
                'has-settings' => false,
                'name' => \__('Access Control Rule: User State', 'graphql-api'),
                'description' => \__('Allow or reject access to the fields and directives based on the user being logged-in or not. It depends on module "Access Control"', 'graphql-api'),
            ],
            [
                'id' => 'access-control-rule-user-roles',
                'enabled' => true,
                'has-settings' => false,
                'name' => \__('Access Control Rule: User Roles', 'graphql-api'),
                'description' => \__('Allow or reject access to the fields and directives based on the user having a certain role. It depends on module "Access Control"', 'graphql-api'),
            ],
            [
                'id' => 'access-control-rule-user-capabilities',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Access Control Rule: User Capabilities', 'graphql-api'),
                'description' => \__('Allow or reject access to the fields and directives based on the user having a certain capability. It depends on module "Access Control"', 'graphql-api'),
            ],
            [
                'id' => 'cache-control',
                'enabled' => true,
                'has-settings' => false,
                'name' => \__('Cache Control', 'graphql-api'),
                'description' => \__('Provide HTTP Caching for Persisted Queries: Cache the response by setting the Cache-Control max-age value, calculated from all fields involved in the query. It depends on module "Persisted Queries"', 'graphql-api'),
            ],
            [
                'id' => 'field-deprecation',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Field Deprecation', 'graphql-api'),
                'description' => \__('User interface to deprecate fields', 'graphql-api'),
            ],
            [
                'id' => 'graphiql-explorer',
                'enabled' => true,
                'has-settings' => false,
                'name' => \__('GraphiQL Explorer', 'graphql-api'),
                'description' => \__('Attach the Explorer widget to the GraphiQL client, to create queries by point-and-clicking on the fields', 'graphql-api'),
            ],
            // [
            //     'id' => 'welcome-guides',
            //     'enabled' => true,
            //     'has-settings' => true,
            //     'name' => \__('Welcome Guides', 'graphql-api'),
            //     'description' => \__('Display welcome guides which demonstrate how to use the plugin\'s different functionalities', 'graphql-api'),
            // ],
            [
                'id' => 'directive-set-convert-lower-uppercase',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Directive Set: Convert Lower/Uppercase', 'graphql-api'),
                'description' => \__('Set of directives to manipulate strings: <code>@upperCase</code>, <code>@lowerCase</code> and <code>@titleCase</code>', 'graphql-api'),
            ],
            [
                'id' => 'schema-post-type',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Schema Post Type', 'graphql-api'),
                'description' => \__('Enable querying for posts in the schema', 'graphql-api'),
            ],
            [
                'id' => 'schema-comment-type',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Schema Comment Type', 'graphql-api'),
                'description' => \__('Enable querying for comments in the schema. It depends on module "Schema Post Type"', 'graphql-api'),
            ],
            [
                'id' => 'schema-user-type',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Schema User Type', 'graphql-api'),
                'description' => \__('Enable querying for users in the schema', 'graphql-api'),
            ],
            [
                'id' => 'schema-page-type',
                'enabled' => true,
                'has-settings' => false,
                'name' => \__('Schema Page Type', 'graphql-api'),
                'description' => \__('Enable querying for pages in the schema', 'graphql-api'),
            ],
            [
                'id' => 'schema-media-type',
                'enabled' => true,
                'has-settings' => true,
                'name' => \__('Schema Media Type', 'graphql-api'),
                'description' => \__('Enable querying for media items in the schema', 'graphql-api'),
            ],
        ];
    }

    /**
     * List of item data
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public function getItems($per_page = 5, $page_number = 1)
    {
        $results = $this->getAllItems();
        return array_splice(
            $results,
            ($page_number - 1) * $per_page,
            $per_page
        );
    }

    /**
     * Enable a module
     *
     * @param int $id module ID
     */
    public function enableModule(string $id): void
    {
        // Do something
    }

    /**
     * Disable a module
     *
     * @param int $id module ID
     */
    public function disableModule(string $id): void
    {
        // Do something
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public function record_count()
    {
        $results = $this->getAllItems();
        return count($results);
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'description':
                return $item[$column_name];
            case 'enabled':
                return \sprintf(
                    '<span role="img" aria-label="%s">%s</span>',
                    $item[$column_name] ? \__('Yes', 'graphql-api') : \__('No', 'graphql-api'),
                    $item[$column_name] ? '✅' : '❌'
                );
        }
        return '';
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-action-items[]" value="%s" />',
            $item['id']
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    public function column_name($item)
    {
        $nonce = \wp_create_nonce( 'graphql_api_enable_or_disable_module' );
        $title = '<strong>' . $item['name'] . '</strong>';
        $linkPlaceholder = '<a href="?page=%s&action=%s&item=%s&_wpnonce=%s">%s</a>';
        $page = esc_attr($_REQUEST['page']);
        // If it is enabled, offer to disable it, or the other way around
        $actions = $item['enabled'] ? [
            'disable' => \sprintf(
                $linkPlaceholder,
                $page,
                'disable',
                $item['id'],
                $nonce,
                \__('Disable', 'graphql-api')
            ),
        ] : [
            'enable' => \sprintf(
                $linkPlaceholder,
                $page,
                'enable',
                $item['id'],
                $nonce,
                \__('Enable', 'graphql-api')
            ),
        ];
        // Maybe add settings links
        if ($item['enabled'] && $item['has-settings']) {
            $actions['settings'] = \sprintf(
                '<a href="%s">%s</a>',
                sprintf(
                    \admin_url(sprintf(
                        'admin.php?page=%s&tab=%s',
                        'graphql_api_settings',
                        $item['id']
                    ))
                ),
                \__('Settings', 'graphql-api')
            );
        }

        return $title . $this->row_actions($actions);
    }


    /**
     *  Associative array of columns
     *
     * @return array
     */
    public function get_columns()
    {
        return [
            'cb' => '<input type="checkbox" />',
            'name' => \__('Name', 'graphql-api'),
            'enabled' => \__('Enabled', 'graphql-api'),
            'description' => \__('Description', 'graphql-api'),
        ];
    }


    // /**
    //  * Columns to make sortable.
    //  *
    //  * @return array
    //  */
    // public function get_sortable_columns()
    // {
    //     $sortable_columns = array(
    //         'description' => array('description', false),
    //     );
    //     return $sortable_columns;
    // }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        return [
            'bulk-enable' => \__('Enable', 'graphql-api'),
            'bulk-disable' => \__('Disable', 'graphql-api'),
        ];
    }


    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_action();

        $per_page = $this->get_items_per_page(
            $this->getItemsPerPageOptionName(),
            $this->getDefaultItemsPerPage()
        );
        $current_page = $this->get_pagenum();
        $total_items  = $this->record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = $this->getItems($per_page, $current_page);
    }

    /**
     * Process bulk and single actions
     *
     * @return void
     */
    public function process_action()
    {
        $bulkActions = array_keys($this->get_bulk_actions());
        $isBulkAction = in_array($_POST['action'], $bulkActions) || in_array($_POST['action2'], $bulkActions);
        /**
         * The Bulk takes precedence, because it's executed as a POST on the current URL
         * Then, the URL can contain an ?action=... which was just executed,
         * and we don't want to execute it again
         */
        if ($isBulkAction) {
            $itemIDs = \esc_sql($_POST['bulk-action-items'] ?? '');
            // Enable or disable
            if ($_POST['action'] == 'bulk-enable' || $_POST['action2'] == 'bulk-enable') {
                foreach ($itemIDs as $id) {
                    $this->enableModule($id);
                }
            } elseif ($_POST['action'] == 'bulk-disable' || $_POST['action2'] == 'bulk-disable') {
                foreach ($itemIDs as $id) {
                    $this->disableModule($id);
                }
            }
            return;
        }
        $singleActions = [
            'enable',
            'disable'
        ];
        $isSingleAction = in_array($this->current_action(), $singleActions);
        if ($isSingleAction) {
            // Verify the nonce
            $nonce = \esc_attr($_REQUEST['_wpnonce']);
            if (!\wp_verify_nonce($nonce, 'graphql_api_enable_or_disable_module')) {
                die(__('This URL is not valid. Please load the page anew, and try again', 'graphql-api'));
            }
            // Enable or disable
            if ('enable' === $this->current_action()) {
                $this->enableModule($_GET['item']);
            } elseif ('disable' === $this->current_action()) {
                $this->disableModule($_GET['item']);
            }
        }
    }

    /**
     * Customize the width of the columns
     */
    public function printStyles(): void
    {
        ?>
        <style type="text/css">
            /* .wp-list-table .column-cb { width: 5%; } */
            .wp-list-table .column-name { width: 25%; }
            .wp-list-table .column-enabled { width: 10%; }
            .wp-list-table .column-description { width: 65%; }
        </style>
        <?php
    }
}
