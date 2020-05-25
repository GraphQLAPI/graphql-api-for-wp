<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\TableActions;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;

/**
 * Module List Table Action
 */
class ModuleListTableAction extends AbstractListTableAction
{
    /**
     * Enable or Disable a list of modules
     *
     * @param array $moduleIDs
     * @param boolean $value
     * @return void
     */
    protected function setModulesEnabledValue(array $moduleIDs, bool $isEnabled): void
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $moduleIDValues = [];
        foreach ($moduleIDs as $moduleID) {
            $moduleIDValues[$moduleID] = $isEnabled;
        }
        $userSettingsManager->setModulesEnabled($moduleIDValues);
    }

    protected function getBulkActions(): array
    {
        return [
            'bulk-enable',
            'bulk-disable'
        ];
    }

    protected function getSingleActions(): array
    {
        return [
            'enable',
            'disable'
        ];
    }

    /**
     * Process bulk and single actions
     *
     * @return void
     */
    public function maybeProcessAction()
    {
        $bulkActions = $this->getBulkActions();
        $isBulkAction = in_array($_POST['action'], $bulkActions) || in_array($_POST['action2'], $bulkActions);
        /**
         * The Bulk takes precedence, because it's executed as a POST on the current URL
         * Then, the URL can contain an ?action=... which was just executed,
         * and we don't want to execute it again
         */
        if ($isBulkAction) {
            if ($moduleIDs = \esc_sql($_POST['bulk-action-items'] ?? '')) {
                // Enable or disable
                if ($_POST['action'] == 'bulk-enable' || $_POST['action2'] == 'bulk-enable') {
                    $this->setModulesEnabledValue($moduleIDs, true);
                } elseif ($_POST['action'] == 'bulk-disable' || $_POST['action2'] == 'bulk-disable') {
                    $this->setModulesEnabledValue($moduleIDs, false);
                }
            }
            return;
        }
        $isSingleAction = in_array($this->currentAction(), $this->getSingleActions());
        if ($isSingleAction) {
            // Verify the nonce
            $nonce = \esc_attr($_REQUEST['_wpnonce']);
            if (!\wp_verify_nonce($nonce, 'graphql_api_enable_or_disable_module')) {
                die(__('This URL is not valid. Please load the page anew, and try again', 'graphql-api'));
            }
            if ($moduleID = $_GET['item']) {
                // Enable or disable
                if ('enable' === $this->currentAction()) {
                    $this->setModulesEnabledValue([$moduleID], true);
                } elseif ('disable' === $this->currentAction()) {
                    $this->setModulesEnabledValue([$moduleID], false);
                }
            }
        }
    }
}
