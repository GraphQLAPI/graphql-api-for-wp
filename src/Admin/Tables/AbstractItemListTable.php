<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\Tables;

/**
 * Module Table
 */
abstract class AbstractItemListTable extends \WP_List_Table
{
    protected $itemsPerPageOptionName = '';
    protected $defaultItemsPerPage = 10;

    public function setItemsPerPageOptionName(string $itemsPerPageOptionName): void
    {
        $this->itemsPerPageOptionName = $itemsPerPageOptionName;
    }
    public function setDefaultItemsPerPage(int $defaultItemsPerPage): void
    {
        $this->defaultItemsPerPage = $defaultItemsPerPage;
    }

    public function getItemsPerPageOptionName(): string
    {
        return $this->itemsPerPageOptionName;
    }
    public function getDefaultItemsPerPage(): int
    {
        return $this->defaultItemsPerPage;
    }

    /**
     * Singular name of the listed records
     *
     * @return string
     */
    abstract public function getItemSingularName(): string;

    /**
     * Plural name of the listed records
     *
     * @return string
     */
    abstract public function getItemPluralName(): string;

    /** Class constructor */
    public function __construct()
    {
        parent::__construct([
            'singular' => $this->getItemSingularName(),
            'plural' => $this->getItemPluralName(),
            'ajax' => false,
        ]);

        add_action('admin_head', [$this, 'printStyles']);
    }

    /**
     * Print custom styles, such as the width of the columns
     */
    public function printStyles(): void
    {
        /**
         * Viewing a table with less than 782px looks really bad, it's buggy.
         * Fix the styles
         */
        ?>
        <style type="text/css">
            @media screen and (max-width: 782px) {
                .wp-list-table tr:not(.inline-edit-row):not(.no-items) td:not(.column-primary)::before {
                    /**
                    * Do not have the title be placed on top of the content
                    */
                    position: static;
                }

                /* Make row actions more easy to select on mobile */
                body:not(.plugins-php) .row-actions {
                    /**
                    * Override grid
                    */
                    display: block;
                    /**
                    * Show always
                    */
                    position: static;
                }
            }
        </style>
        <?php
    }


    /**
     * Text displayed when there are no items
     *
     * @return void
     */
    public function no_items()
    {
        _e('No items found.', 'graphql-api');
    }
}
