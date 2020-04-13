<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

/**
 * Menu Page
 */
interface MenuPageInterface
{
    /**
     * Print the menu page HTML content
     *
     * @return void
     */
    public function print(): void;
}
