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
        // Do nothing
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
