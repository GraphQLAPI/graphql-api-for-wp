<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\Tables;

use GraphQLAPI\GraphQLAPI\Admin\TableActions\ModuleListTableAction;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Module Table
 */
class ModuleListTable extends AbstractItemListTable
{
    /** Class constructor */
    public function __construct()
    {
        parent::__construct();

        /**
         * If executing an operation, print a success message
         */
        \add_action('admin_notices', function () {
            $this->maybeAddAdminNotice();
        });
    }
    /**
     * Singular name of the listed records
     *
     * @return string
     */
    public function getItemSingularName(): string
    {
        return \__('Module', 'graphql-api');
    }

    /**
     * Plural name of the listed records
     *
     * @return string
     */
    public function getItemPluralName(): string
    {
        return \__('Modules', 'graphql-api');
    }

    /**
     * Return all the items to display on the table
     *
     * @return array
     */
    public function getAllItems(): array
    {
        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules();
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $isEnabled = $moduleRegistry->isModuleEnabled($module);
            $items[] = [
                'id' => $moduleResolver->getID($module),
                'is-enabled' => $isEnabled,
                'can-be-enabled' => !$isEnabled && $moduleRegistry->canModuleBeEnabled($module),
                'has-settings' => $moduleResolver->hasSettings($module),
                'name' => $moduleResolver->getName($module),
                'description' => $moduleResolver->getDescription($module),
                'depends-on' => $moduleResolver->getDependedModuleLists($module),
                'url' => $moduleResolver->getURL($module),
            ];
        }
        return $items;
    }

    /**
     * List of item data
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public function getItems($per_page = 5, $page_number = 1)
    {
        $results = $this->getAllItems();
        return array_splice(
            $results,
            ($page_number - 1) * $per_page,
            $per_page
        );
    }

    /**
     * Show an admin notice with the successful message
     * Executing this function from within `setModulesEnabledValue` is too late,
     * since hook "admin_notices" will have been executed by then
     * Then, deduce if there will be an operation, and always say "successful"
     *
     * @return void
     */
    public function maybeAddAdminNotice(): void
    {
        /**
         * See if executing any of the actions
         */
        $bulkActions = $this->getBulkActions();
        $isBulkAction = in_array($_POST['action'], $bulkActions) || in_array($_POST['action2'], $bulkActions);
        $isSingleAction = in_array($this->current_action(), $this->getSingleActions());
        if ($isBulkAction || $isSingleAction) {
            _e(sprintf(
                '<div class="notice notice-success is-dismissible">' .
                    '<p>%s</p>' .
                '</div>',
                sprintf(
                    __('[GraphQL API] Operation successful. You may need to <a href="%s">refresh the page</a> to see the changes.', 'graphql-api'),
                    \admin_url(sprintf(
                        'admin.php?page=%s',
                        'graphql_api_modules'
                    ))
                )
            ));
        }
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public function record_count()
    {
        $results = $this->getAllItems();
        return count($results);
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'description':
                return $item[$column_name];
            case 'depends-on':
                // Output the list with AND lists of dependencies
                // Each list is an OR list of depended modules
                // It's formatted like this: module1, module2, ..., module5 or module6
                $items = [];
                $moduleRegistry = ModuleRegistryFacade::getInstance();
                $dependedModuleLists = $item[$column_name];
                /**
                 * This is a list of lists of modules, as to model both OR and AND conditions
                 */
                foreach ($dependedModuleLists as $dependedModuleList) {
                    if (!$dependedModuleList) {
                        continue;
                    }
                    $dependedModuleListNames = array_map(
                        function ($dependedModule) use ($moduleRegistry) {
                            $moduleResolver = $moduleRegistry->getModuleResolver($dependedModule);
                            return '▹ ' . $moduleResolver->getName($dependedModule);
                        },
                        $dependedModuleList
                    );
                    if (count($dependedModuleListNames) >= 2) {
                        $lastElem = array_pop($dependedModuleListNames);
                        $commaElems = implode(
                            \__(', ', 'graphql-api'),
                            $dependedModuleListNames
                        );
                        $items[] = sprintf(
                            \__('%s or %s', 'graphql-api'),
                            $commaElems,
                            $lastElem
                        );
                    } else {
                        $items[] = $dependedModuleListNames[0];
                    }
                }
                return implode('<br/>', $items);
            case 'enabled':
                return \sprintf(
                    '<span role="img" aria-label="%s">%s</span>',
                    $item['is-enabled'] ? \__('Yes', 'graphql-api') : \__('No', 'graphql-api'),
                    $item['is-enabled'] ? '✅' : '❌'
                );
        }
        return '';
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-action-items[]" value="%s" />',
            $item['id']
        );
    }

    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    public function column_name($item)
    {
        $nonce = \wp_create_nonce( 'graphql_api_enable_or_disable_module' );
        $title = '<strong>' . $item['name'] . '</strong>';
        $linkPlaceholder = '<a href="?page=%s&action=%s&item=%s&_wpnonce=%s">%s</a>';
        $page = esc_attr($_REQUEST['page']);
        $actions = [];
        if ($item['is-enabled']) {
            // If it is enabled, offer to disable it
            $actions['disable'] = \sprintf(
                $linkPlaceholder,
                $page,
                'disable',
                $item['id'],
                $nonce,
                \__('Disable', 'graphql-api')
            );

            // Maybe add settings links
            if ($item['has-settings']) {
                $actions['settings'] = \sprintf(
                    '<a href="%s">%s</a>',
                    sprintf(
                        \admin_url(sprintf(
                            'admin.php?page=%s&tab=%s',
                            'graphql_api_settings',
                            $item['id']
                        ))
                    ),
                    \__('Settings', 'graphql-api')
                );
            }
        } elseif ($item['can-be-enabled']) {
            // If not enabled and can be enabled, offer to do it
            $actions['enable'] = \sprintf(
                $linkPlaceholder,
                $page,
                'enable',
                $item['id'],
                $nonce,
                \__('Enable', 'graphql-api')
            );
        } else {
            // Not enabled and can't be enabled, mention requirements not met
            // Not enabled for "striped" table style because, without a link, color contrast is not good:
            // gray font color over gray background
            // if ($this->usePluginTableStyle()) {
            $actions['disabled'] = \__('Disabled', 'graphql-api');
            // }
        }
        // Add a link to the website, to read the component's documentation
        if ($url = $item['url']) {
            $actions['details'] = \sprintf(
                '<a href="%s">%s</a>',
                $url,
                \__('View details', 'graphql-api')
            );
        }
        return $title . $this->row_actions($actions/*, $this->usePluginTableStyle()*/);
    }

    /**
     * Indicate if to show the enabled column or not
     *
     * @return array
     */
    protected function usePluginTableStyle(): bool
    {
        return true;
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    public function get_columns()
    {
        return array_merge(
            [
                'cb' => '<input type="checkbox" />',
                'name' => \__('Module', 'graphql-api'),
            ],
            $this->usePluginTableStyle() ?
                [] :
                [
                    'enabled' => \__('Enabled', 'graphql-api'),
                ],
            [
                'description' => \__('Description', 'graphql-api'),
                'depends-on' => \__('Depends on', 'graphql-api'),
            ]
        );
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        return [
            'bulk-enable' => \__('Enable', 'graphql-api'),
            'bulk-disable' => \__('Disable', 'graphql-api'),
        ];
    }

    /**
     * Get a list of CSS classes for the WP_List_Table table tag.
     *
     * @since 3.1.0
     *
     * @return string[] Array of CSS classes for the table tag.
     */
    protected function get_table_classes()
    {
        // return array_merge(
        //     parent::get_table_classes(),
        //     [
        //         'plugins'
        //     ]
        // );
        if ($this->usePluginTableStyle()) {
            return array( 'widefat', 'plugins', $this->_args['plural'] );
        }
        return array_diff(
            parent::get_table_classes(),
            [
                'fixed'
            ]
        );
    }

    /**
     * Generates content for a single row of the table
     *
     * @since 3.1.0
     *
     * @param object $item The current item
     */
    public function single_row($item)
    {
        if ($this->usePluginTableStyle()) {
            echo sprintf(
                '<tr class="%s">',
                $item['is-enabled'] ? 'active' : 'inactive'
            );
            $this->single_row_columns($item);
            echo '</tr>';
        } else {
            parent::single_row($item);
        }
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        /** Process bulk or single action */
        $instanceManager = InstanceManagerFacade::getInstance();
        $tableAction = $instanceManager->getInstance(ModuleListTableAction::class);
        $tableAction->maybeProcessAction();

        $per_page = $this->get_items_per_page(
            $this->getItemsPerPageOptionName(),
            $this->getDefaultItemsPerPage()
        );
        $current_page = $this->get_pagenum();
        $total_items  = $this->record_count();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = $this->getItems($per_page, $current_page);
    }

    protected function getBulkActions(): array
    {
        return array_keys($this->get_bulk_actions());
    }

    protected function getSingleActions(): array
    {
        return [
            'enable',
            'disable'
        ];
    }

    /**
     * Customize the width of the columns
     */
    public function printStyles(): void
    {
        ?>
        <style type="text/css">
            .row-actions span.disabled { color: #969696; }
            .plugins .name { font-weight: normal; }
        </style>
        <?php
        /*
        if ($this->usePluginTableStyle()) {
            ?>
            <style type="text/css">
                .wp-list-table .column-name { width: 25%; }
                .wp-list-table .column-description { width: 75%; }
            </style>
            <?php
        } else {
            ?>
            <style type="text/css">
                .wp-list-table .column-name { width: 25%; }
                .wp-list-table .column-enabled { width: 10%; }
                .wp-list-table .column-description { width: 65%; }
            </style>
            <?php
        }
        */
    }
}
