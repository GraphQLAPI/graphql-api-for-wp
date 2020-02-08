<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\MenuPageInterface;

/**
 * Menu page
 */
abstract class AbstractMenuPage implements MenuPageInterface {

    public function __construct()
    {
        add_action(
            'admin_init',
            [$this, 'init']
        );
    }
    /**
     * Initialize the menu page
     *
     * @return void
     */
    public function init(): void
    {
        // Function to be overriden
    }
}
