<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\Tables;

use GraphQLAPI\GraphQLAPI\Admin\MenuPages\ModulesMenuPage;

/**
 * Module Table
 */
class ModuleTable extends \WP_List_Table
{
    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => \__( 'Customer', 'sp' ), //singular name of the listed records
            'plural'   => \__( 'Customers', 'sp' ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ]);
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_customers($per_page = 5, $page_number = 1)
    {
        $results = [
            ['id' => 1, 'name' => 'Alfreds Futterkiste ', 'address' => 'Obere Str. 57', 'city' => 'Berlin'],
            ['id' => 2, 'name' => 'Ana Trujillo ', 'address' => 'Avda. de la Constitución 2222', 'city' => 'México D.F'],
            ['id' => 3, 'name' => 'Antonio Moreno', 'address' => 'Mataderos 2312', 'city' => 'México D.F'],
            ['id' => 4, 'name' => 'Thomas Hardy ', 'address' => '120 Hanover Sq.', 'city' => 'London'],
            ['id' => 5, 'name' => 'Christina Berglund ', 'address' => 'Berguvsvägen 8 ', 'city' => 'Lulea'],
            ['id' => 9, 'name' => 'Hanna Moos', 'address' => 'Forsterstr. 57', 'city' => 'Mannheim'],
            ['id' => 10,'name' =>  'Frédérique Citeaux', 'address' => '24, place Kléber', 'city' => 'Strasbourg'],
            ['id' => 11,'name' =>  'Martín Sommer', 'address' => 'C/ Araquil, 67', 'city' => 'Madrid'],
            ['id' => 12,'name' =>  'Laurence Lebihans', 'address' => '12, rue des Bouchers', 'city' => 'Marseille'],
        ];
        return array_splice(
            $results,
            ($page_number - 1) * $per_page,
            $per_page
        );
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_customer($id)
    {
        // Do something
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

    /** Text displayed when no customer data is available */
    public function no_items()
    {
        _e('No customers avaliable.', 'sp');
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
            case 'address':
            case 'city':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb($item)
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
    function column_name($item)
    {
        $delete_nonce = \wp_create_nonce( 'sp_delete_customer' );

        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = [
            'delete' => \sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
        ];

        return $title . $this->row_actions($actions);
    }


    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns()
    {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'name'    => \__( 'Name', 'sp' ),
            'address' => \__( 'Address', 'sp' ),
            'city'    => \__( 'City', 'sp' )
        ];

        return $columns;
    }


    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'city' => array('city', false),
        );
        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => 'Delete'
        ];
        return $actions;
    }


    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page(ModulesMenuPage::SCREEN_OPTION_NAME, 5);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args([
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page //WE have to determine how many items to show on a page
        ]);

        $this->items = self::get_customers($per_page, $current_page);
    }

    public function process_bulk_action()
    {
        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = \esc_attr($_REQUEST['_wpnonce']);

            if (!\wp_verify_nonce($nonce, 'sp_delete_customer')) {
                die( 'Go get a life script kiddies' );
            } else {
                self::delete_customer(absint($_GET['customer']));

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url
                \wp_redirect(\esc_url_raw(\add_query_arg()));
                exit;
            }

        }

        // If the delete bulk action is triggered
        if (
            (isset($_POST['action']) && $_POST['action'] == 'bulk-delete') ||
            (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
        ) {
            $delete_ids = \esc_sql($_POST['bulk-delete']);

            // loop over the array of record IDs and delete them
            foreach ($delete_ids as $id) {
                self::delete_customer($id);
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            \wp_redirect(\esc_url_raw(\add_query_arg()));
            exit;
        }
    }
}
