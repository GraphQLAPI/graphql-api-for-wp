<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Table menu page
 */
abstract class AbstractTableMenuPage extends AbstractMenuPage
{
    protected $tableObject;

    abstract protected function getHeader(): string;

    public function print(): void
    {
        ?>
        <div class="wrap">
            <h1><?php echo $this->getHeader() ?></h1>
            <form method="post">
                <?php
                $this->tableObject->prepare_items();
                $this->tableObject->display(); ?>
            </form>
        </div>
        <?php
    }

    protected function showScreenOptions(): bool
    {
        return false;
    }

    protected function getScreenOptionLabel(): string
    {
        return $this->getHeader();
    }
    protected function getScreenOptionDefault(): int
    {
        return 999;
    }
    protected function getScreenOptionName(): string
    {
        return str_replace(' ', '_', strtolower($this->getScreenOptionLabel())) . '_per_page';
    }

    abstract protected function getTableClass(): string;

    public function initializeTable(): void
    {
        /**
         * Screen options
         */
        if ($this->showScreenOptions()) {
            /**
             * Set-up the screen options
             */
            $option = 'per_page';
            $args = [
                'label' => $this->getScreenOptionLabel(),
                'default' => $this->getScreenOptionDefault(),
                'option'  => $this->getScreenOptionName(),
            ];
            \add_screen_option($option, $args);
        }

        /**
         * Instantiate the table object
         */
        $instanceManager = InstanceManagerFacade::getInstance();
        $this->tableObject = $instanceManager->getInstance($this->getTableClass());
        /**
         * Set properties
         */
        $this->tableObject->setItemsPerPageOptionName($this->getScreenOptionName());
        $this->tableObject->setDefaultItemsPerPage($this->getScreenOptionDefault());
    }

    public function initialize(): void
    {
        parent::initialize();

        if ($this->showScreenOptions()) {
            /**
             * Save the screen options
             */
            \add_filter(
                'set-screen-option',
                function ($status, $option, $value) {
                    return $value;
                },
                10,
                3
            );
        } else {
            /**
             * Remove the Screen Options tab
             */
            \add_filter('screen_options_show_screen', '__return_false');
        }

        /**
         * Priority 30: execute after `addMenuPagesBottom`, so by then we have the hookName
         */
        \add_action(
            'admin_menu',
            function () {
                /**
                 * Attach to the hook corresponding to this page
                 */
                \add_action(
                    'load-' . $this->getHookName(),
                    [$this, 'initializeTable']
                );
            },
            30
        );
    }
}
