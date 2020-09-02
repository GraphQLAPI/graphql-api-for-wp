<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

/**
 * Menu Page
 */
interface MenuPageInterface
{
    /**
     * Initialize menu page. Function to override
     */
    public function initialize(): void;
    /**
     * Print the menu page HTML content
     *
     * @return void
     */
    public function print(): void;
    public function getScreenID(): string;
}
