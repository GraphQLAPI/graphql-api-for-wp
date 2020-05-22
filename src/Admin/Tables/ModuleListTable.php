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

    /**
     * List of item data
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function getItems($per_page = 5, $page_number = 1)
    {
        $results = [
            ['ID' => 'a1', 'name' => 'Alfreds Futterkiste ', 'description' => 'nearly all default'],
            ['ID' => 'a2', 'name' => 'Ana Trujillo ', 'description' => 'But creating one'],
            ['ID' => 'a3', 'name' => 'Antonio Moreno', 'description' => 'done it before'],
            ['ID' => 'a4', 'name' => 'Thomas Hardy ', 'description' => 'WordPress provides functionality'],
            ['ID' => 'a5', 'name' => 'Christina Berglund ', 'description' => 'The WordPress Admin'],
            ['ID' => 'a9', 'name' => 'Hanna Moos', 'description' => 'handbook, in'],
            ['ID' => 'a10','name' =>  'Frédérique Citeaux', 'description' => 'with common traps'],
            ['ID' => 'a11','name' =>  'Martín Sommer', 'description' => 'Presentation Of A'],
            ['ID' => 'a12','name' =>  'Laurence Lebihans', 'description' => 'better understand the'],
        ];
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
        // echo 'enableModule with id '.$id;
    }

    /**
     * Disable a module
     *
     * @param int $id module ID
     */
    public function disableModule(string $id): void
    {
        // Do something
        // echo 'disableModule with id '.$id;
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count()
    {
        return 9;
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
            '<input type="checkbox" name="bulk-delete[]" value="%s" />',
            $item['ID']
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
        $delete_nonce = \wp_create_nonce( 'graphql_api_enable_or_disable_module' );

        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = [
            'delete' => \sprintf(
                '<a href="?page=%s&action=%s&item=%s&_wpnonce=%s">Delete</a>',
                esc_attr($_REQUEST['page']),
                'delete',
                $item['ID'],
                $delete_nonce
            ),
        ];

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
            'bulk-delete' => 'Delete'
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
        $total_items  = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = self::getItems($per_page, $current_page);
    }

    /**
     * Process actions
     *
     * @return void
     */
    public function process_action()
    {
        // Detect when a bulk action is being triggered...
        $isSingleAction = 'delete' === $this->current_action() || 'delete' === $this->current_action();
        $isBulkAction =
            (isset($_POST['action']) && $_POST['action'] == 'bulk-delete') ||
            (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete');
        if ($isSingleAction || $isBulkAction) {
            if ($isSingleAction) {
                // Verify the nonce
                $nonce = \esc_attr($_REQUEST['_wpnonce']);
                if (!\wp_verify_nonce($nonce, 'graphql_api_enable_or_disable_module')) {
                    die(__('This URL is not valid. Please load the page anew, and try again', 'graphql-api'));
                }
                if ('delete' === $this->current_action()) {
                    self::enableModule($_GET['item']);
                } else {
                    self::disableModule($_GET['item']);
                }
            } elseif ($isBulkAction) {
                $itemIDs = \esc_sql($_POST['bulk-delete']);

                if (true) {
                    foreach ($itemIDs as $id) {
                        self::enableModule($id);
                    }
                } else {
                    foreach ($itemIDs as $id) {
                        self::disableModule($id);
                    }
                }
            }
        }
    }
}
