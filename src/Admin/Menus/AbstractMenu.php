<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Admin\Menus;

/**
 * Admin menu class
 */
abstract class AbstractMenu
{
    public function __construct()
    {
        $this->init();
    }

    abstract public static function getName();

    /**
     * Initialize the endpoints
     *
     * @return void
     */
    protected function init(): void
    {
        /**
         * Low priority to execute before adding the menus for the CPTs
         */
        \add_action(
            'admin_menu',
            [$this, 'addMenuPagesTop'],
            9
        );
        /**
         * High priority to execute after adding the menus for the CPTs
         */
        \add_action(
            'admin_menu',
            [$this, 'addMenuPagesBottom'],
            20
        );
    }
    public function addMenuPagesTop(): void
    {
        // Initially empty
    }

    public function addMenuPagesBottom(): void
    {
        // Initially empty
    }
}
