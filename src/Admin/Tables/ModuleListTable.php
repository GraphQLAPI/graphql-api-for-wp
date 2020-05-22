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
            ['id' => 'Custom-Endpoints', 'enabled' => true, 'has-settings' => true, 'name' => 'Custom Endpoints', 'description' => 'So, here I tell you about Custom Endpoints, oh yeah you know'],
            ['id' => 'Persisted-Queries', 'enabled' => true, 'has-settings' => true, 'name' => 'Persisted Queries', 'description' => 'So, here I tell you about Persisted Queries, oh yeah you know'],
            ['id' => 'Access-Control', 'enabled' => true, 'has-settings' => false, 'name' => 'Access Control', 'description' => 'So, here I tell you about Access Control, oh yeah you know'],
            ['id' => 'Cache-Control-for-Persisted-Queries', 'enabled' => true, 'has-settings' => false, 'name' => 'Cache Control for Persisted Queries', 'description' => 'So, here I tell you about Cache Control for Persisted Queries, oh yeah you know'],
            ['id' => 'Field-Deprecation', 'enabled' => true, 'has-settings' => true, 'name' => 'Field Deprecation', 'description' => 'So, here I tell you about Field Deprecation, oh yeah you know'],
            ['id' => 'GraphiQL-for-custom-endpoint', 'enabled' => true, 'has-settings' => true, 'name' => 'GraphiQL for custom endpoint', 'description' => 'So, here I tell you about GraphiQL for custom endpoint, oh yeah you know'],
            ['id' => 'Interactive-schema-for-custom-endpoint', 'enabled' => true, 'has-settings' => true, 'name' => 'Interactive schema for custom endpoint', 'description' => 'So, here I tell you about Interactive schema for custom endpoint, oh yeah you know'],
            ['id' => 'Access-Control-by-User-State', 'enabled' => true, 'has-settings' => false, 'name' => 'Access Control by User State', 'description' => 'So, here I tell you about Access Control by User State, oh yeah you know'],
            ['id' => 'Access-Control-by-User-Roles', 'enabled' => true, 'has-settings' => false, 'name' => 'Access Control by User Roles', 'description' => 'So, here I tell you about Access Control by User Roles, oh yeah you know'],
            ['id' => 'Access-Control-by-User-Capabilities', 'enabled' => true, 'has-settings' => true, 'name' => 'Access Control by User Capabilities', 'description' => 'So, here I tell you about Access Control by User Capabilities, oh yeah you know'],
            ['id' => 'Access-Control---Remove-Access', 'enabled' => true, 'has-settings' => true, 'name' => 'Access Control - Remove Access', 'description' => 'So, here I tell you about Access Control - Remove Access, oh yeah you know'],
            ['id' => 'Explorer-in-GraphiQL', 'enabled' => true, 'has-settings' => false, 'name' => 'Explorer in GraphiQL', 'description' => 'So, here I tell you about Explorer in GraphiQL, oh yeah you know'],
            ['id' => 'Welcome-Guides', 'enabled' => true, 'has-settings' => true, 'name' => 'Welcome Guides', 'description' => 'So, here I tell you about Welcome Guides, oh yeah you know'],
            ['id' => 'String-manipulation-directives', 'enabled' => true, 'has-settings' => true, 'name' => 'String manipulation directives', 'description' => 'So, here I tell you about String manipulation directives, oh yeah you know'],
            ['id' => 'Schema-Post-Type', 'enabled' => true, 'has-settings' => true, 'name' => 'Schema Post Type', 'description' => 'So, here I tell you about Schema Post Type, oh yeah you know'],
            ['id' => 'Schema-User-Type', 'enabled' => true, 'has-settings' => true, 'name' => 'Schema User Type', 'description' => 'So, here I tell you about Schema User Type, oh yeah you know'],
            ['id' => 'Schema-Comment-Type', 'enabled' => true, 'has-settings' => true, 'name' => 'Schema Comment Type', 'description' => 'So, here I tell you about Schema Comment Type, oh yeah you know'],
            ['id' => 'Schema-Media-Type', 'enabled' => true, 'has-settings' => true, 'name' => 'Schema Media Type', 'description' => 'So, here I tell you about Schema Media Type, oh yeah you know'],
            ['id' => 'Schema-Page-Type', 'enabled' => true, 'has-settings' => false, 'name' => 'Schema Page Type', 'description' => 'So, here I tell you about Schema Page Type, oh yeah you know'],
            ['id' => 'Single-endpoint', 'enabled' => false, 'has-settings' => true, 'name' => 'Single endpoint', 'description' => 'So, here I tell you about Single endpoint, oh yeah you know'],
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
                return $item[$column_name] ? '✅' : '❌';
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
        if ($item['has-settings']) {
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
            /* .wp-list-table .column-name { width: 20%; } */
            /* .wp-list-table .column-enabled { width: 5%; } */
            .wp-list-table .column-description { width: 70%; }
        </style>
        <?php
    }
}
