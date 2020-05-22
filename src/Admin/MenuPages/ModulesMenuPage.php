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
            <h2>WP_List_Table Class Example</h2>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
                                <?php
                                $this->tableObject->prepare_items();
                                $this->tableObject->display(); ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
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
            'default' => 5,
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

        $hook = 'graphql-api_page_graphql_api_modules';
        \add_action(
            "load-$hook",
            [$this, 'screenOption']
        );
        // $this->tableObject->initialize();

        // \add_action( "load-$hook", [ $this, 'screenOption' ] );

        \add_action('admin_menu', function () {
            $this->screenOption();
        });
    }
}
