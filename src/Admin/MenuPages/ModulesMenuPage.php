<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\Tables\ModuleTable;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\AbstractMenuPage;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Module menu page
 */
class ModulesMenuPage extends AbstractMenuPage
{
    protected $tableObject;

    // public function __construct()
    // {
    //     $instanceManager = InstanceManagerFacade::getInstance();
    //     $this->tableObject = $instanceManager->getInstance(ModuleTable::class);
    // }

    public function print(): void
    {
        ?>
        <div class="wrap">
            <h1><?php \_e('GraphQL API — Modules', 'graphql-api'); ?></h1>
            <form method="post">
                <?php
                $this->tableObject->prepare_items();
                $this->tableObject->display(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Screen options
     */
    public function screenOption()
    {
        $option = 'per_page';
        $args = [
            'label'   => 'Customers',
            'default' => 999,
            'option'  => 'customers_per_page'
        ];
        \add_screen_option($option, $args);

        $instanceManager = InstanceManagerFacade::getInstance();
        $this->tableObject = $instanceManager->getInstance(ModuleTable::class);
    }

    public static function setScreen($status, $option, $value)
    {
        return $value;
    }

    public function initialize(): void
    {
        parent::initialize();

        \add_filter(
            'set-screen-option',
            [self::class, 'setScreen'],
            10,
            3
        );

        /**
         * Priority 30: execute after `addMenuPagesBottom`, so by then we have the hookName
         */
        \add_action(
            'admin_menu',
            function () {
                // $this->screenOption();
                /**
                 * Get the hookname from when the page was registered
                 */
                \add_action(
                    'load-' . $this->getHookName(),
                    [$this, 'screenOption']
                );
            },
            30
        );
    }
}
