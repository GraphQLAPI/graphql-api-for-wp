<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use Leoloso\GraphQLByPoPWPPlugin\QueryExecution\AccessControlGraphQLQueryConfigurator;
use Leoloso\GraphQLByPoPWPPlugin\QueryExecution\FieldDeprecationGraphQLQueryConfigurator;

abstract class AbstractQueryExecutionSchemaConfigurator extends AbstractSchemaConfigurator
{
    /**
     * Initialize the configuration of services before the execution of the GraphQL query
     *
     * @return void
     */
    protected function doInit(): void
    {
        /**
         * The endpoint/persisted query CPTs contain the Schema configuration block
         * Execute it
         */
        $this->readSchemaConfigurationBlock();
    }

    /**
     * Extract the items defined in the Schema Configuration,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    protected function readSchemaConfigurationBlock(): void
    {
        // If we found a CCL, load its rules/restrictions
        global $post;
        $schemaConfigurationBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $post,
            PluginState::getSchemaConfigurationBlock()
        );
        // If there was no schema configuration, use the default one
        if (is_null($schemaConfigurationBlockDataItem)) {
            // If there are none, use the default
            $schemaConfigurationBlockDataItem = [
                'attrs' => [
                    'schemaConfiguration' => 'default',
                ]
            ];
        }

        $schemaConfiguration = $schemaConfigurationBlockDataItem['attrs']['schemaConfiguration'];
        // $schemaConfiguration is either an ID or one of the meta options (default, none, inherit)
        if ($schemaConfiguration == 'none') {
            return;
        } elseif ($schemaConfiguration == 'default') {
            $schemaConfigurationID = 824;
        } elseif ($schemaConfiguration == 'inherit') {
            $schemaConfigurationID = 824;
        } else {
            $schemaConfigurationID = $schemaConfiguration;
        }

        // Get that Schema Configuration, and load its settings
        $this->executeSchemaConfiguration($schemaConfigurationID);
    }

    /**
     * Apply all the settings defined in the Schema Configuration:
     * - Access Control Lists
     * - Field Deprecation Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $this->executeSchemaConfigurationAccessControlLists($schemaConfigurationID);
        $this->executeSchemaConfigurationFieldDeprecationLists($schemaConfigurationID);
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Access Control Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationAccessControlLists(int $schemaConfigurationID): void
    {
        $schemaConfigACLBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            PluginState::getSchemaConfigAccessControlListBlock()
        );
        if (!is_null($schemaConfigACLBlockDataItem)) {
            if ($accessControlLists = $schemaConfigACLBlockDataItem['attrs']['accessControlLists']) {
                $configurator = new AccessControlGraphQLQueryConfigurator();
                foreach ($accessControlLists as $accessControlListID) {
                    $configurator->executeConfiguration($accessControlListID);
                }
            }
        }
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Field Deprecation Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationFieldDeprecationLists(int $schemaConfigurationID): void
    {
        $schemaConfigFDLBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            PluginState::getSchemaConfigFieldDeprecationListBlock()
        );
        if (!is_null($schemaConfigFDLBlockDataItem)) {
            if ($fieldDeprecationLists = $schemaConfigFDLBlockDataItem['attrs']['fieldDeprecationLists']) {
                $configurator = new FieldDeprecationGraphQLQueryConfigurator();
                foreach ($fieldDeprecationLists as $fieldDeprecationListID) {
                    $configurator->executeConfiguration($fieldDeprecationListID);
                }
            }
        }
    }
}
