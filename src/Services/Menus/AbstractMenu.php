<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Menus;

use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;

/**
 * Admin menu class
 */
abstract class AbstractMenu extends AbstractAutomaticallyInstantiatedService
{
    abstract public function getName(): string;

    /**
     * Initialize the endpoints
     *
     * @return void
     */
    public function initialize(): void
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