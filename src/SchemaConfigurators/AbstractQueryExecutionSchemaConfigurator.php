<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigAccessControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigFieldDeprecationListBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigurationBlock;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\FieldDeprecationGraphQLQueryConfigurator;
use Leoloso\GraphQLByPoPWPPlugin\Settings\Settings;

abstract class AbstractQueryExecutionSchemaConfigurator implements SchemaConfiguratorInterface
{
    /**
     * Extract the items defined in the Schema Configuration,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    public function executeSchemaConfiguration(int $customPostID): void
    {
        if ($schemaConfigurationID = $this->getSchemaConfigurationID($customPostID)) {
            // Get that Schema Configuration, and load its settings
            $this->executeSchemaConfigurationItems($schemaConfigurationID);
        }
    }
    /**
     * Extract the Schema Configuration ID from the block stored in the post
     *
     * @return void
     */
    protected function getSchemaConfigurationID(int $customPostID): ?int
    {
        $schemaConfigurationBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $customPostID,
            PluginState::getSchemaConfigurationBlock()
        );
        // If there was no schema configuration, then the default one has been selected
        // It is not saved in the DB, because it has been set as the default value in
        // blocks/schema-configuration/src/index.js
        if (is_null($schemaConfigurationBlockDataItem)) {
            return Settings::getDefaultSchemaConfiguration();
        }

        $schemaConfiguration = $schemaConfigurationBlockDataItem['attrs'][SchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION];
        // Check if $schemaConfiguration is one of the meta options (default, none, inherit)
        if ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            return null;
        } elseif ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            return Settings::getDefaultSchemaConfiguration();
        } elseif ($schemaConfiguration == SchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            // Return the schema configuration from the parent, or null if no parent exists
            $customPost = \get_post($customPostID);
            if ($customPost->post_parent) {
                return $this->getSchemaConfigurationID($customPost->post_parent);
            }
            return null;
        }
        // It is already the ID
        return $schemaConfiguration;
    }

    /**
     * Apply all the settings defined in the Schema Configuration:
     * - Access Control Lists
     * - Field Deprecation Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
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
            if ($accessControlLists = $schemaConfigACLBlockDataItem['attrs'][SchemaConfigAccessControlListBlock::ATTRIBUTE_NAME_ACCESS_CONTROL_LISTS]) {
                $configurator = new AccessControlGraphQLQueryConfigurator();
                foreach ($accessControlLists as $accessControlListID) {
                    $configurator->executeSchemaConfiguration($accessControlListID);
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
            if ($fieldDeprecationLists = $schemaConfigFDLBlockDataItem['attrs'][SchemaConfigFieldDeprecationListBlock::ATTRIBUTE_NAME_FIELD_DEPRECATION_LISTS]) {
                $configurator = new FieldDeprecationGraphQLQueryConfigurator();
                foreach ($fieldDeprecationLists as $fieldDeprecationListID) {
                    $configurator->executeSchemaConfiguration($fieldDeprecationListID);
                }
            }
        }
    }
}
