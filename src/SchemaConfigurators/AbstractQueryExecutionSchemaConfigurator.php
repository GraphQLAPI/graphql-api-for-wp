<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use PoP\AccessControl\Schema\SchemaModes;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\Settings\Settings;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigurationBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigAccessControlListBlock;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigFieldDeprecationListBlock;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\FieldDeprecationGraphQLQueryConfigurator;

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
        // Check if it is enabled
        if (!$this->isEnabled($customPostID)) {
            return;
        }
        if ($schemaConfigurationID = $this->getSchemaConfigurationID($customPostID)) {
            // Get that Schema Configuration, and load its settings
            $this->executeSchemaConfigurationItems($schemaConfigurationID);
        }
    }
    abstract protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock;
    /**
     * Read the options block and check the value of attribute "isEnabled"
     *
     * @return void
     */
    protected function isEnabled(int $customPostID): bool
    {
        $optionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $customPostID,
            $this->getQueryExecutionOptionsBlock()
        );
        // If there was no options block, something went wrong in the post content
        if (is_null($optionsBlockDataItem)) {
            return false;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][AbstractQueryExecutionOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED] ?? true;
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
     * - Options
     * - Access Control Lists
     * - Field Deprecation Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
    {
        $this->executeSchemaConfigurationOptions($schemaConfigurationID);
        $this->executeSchemaConfigurationAccessControlLists($schemaConfigurationID);
        $this->executeSchemaConfigurationFieldDeprecationLists($schemaConfigurationID);
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Options
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationOptions(int $schemaConfigurationID): void
    {
        $this->executeSchemaConfigurationOptionsNamespacing($schemaConfigurationID);
        $this->executeSchemaConfigurationOptionsDefaultSchemaMode($schemaConfigurationID);
    }

    /**
     * Apply the Namespacing settings
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationOptionsNamespacing(int $schemaConfigurationID): void
    {
        $schemaConfigOptionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            PluginState::getSchemaConfigOptionsBlock()
        );
        if (!is_null($schemaConfigOptionsBlockDataItem)) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $useNamespacing = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_USE_NAMESPACING];
            // Only execute if it has value "enabled" or "disabled".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (!in_array($useNamespacing, [
                SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED,
                SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED,
            ])) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(
                ComponentModelComponentConfiguration::class,
                ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES
            );
            \add_filter(
                $hookName,
                function () use ($useNamespacing) {
                    return $useNamespacing == SchemaConfigOptionsBlock::ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED;
                },
                PHP_INT_MAX
            );
        }
    }

    /**
     * Apply the Default Schema Mode settings
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationOptionsDefaultSchemaMode(int $schemaConfigurationID): void
    {
        $schemaConfigOptionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            PluginState::getSchemaConfigOptionsBlock()
        );
        if (!is_null($schemaConfigOptionsBlockDataItem)) {
            /**
             * Default value (if not defined in DB): `default`. Then do nothing
             */
            $defaultSchemaMode = $schemaConfigOptionsBlockDataItem['attrs'][SchemaConfigOptionsBlock::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE];
            // Only execute if it has value "public" or "private".
            // If "default", then the general settings will already take effect, so do nothing
            // (And if any other unsupported value, also do nothing)
            if (!in_array($defaultSchemaMode, [
                SchemaModes::PUBLIC_SCHEMA_MODE,
                SchemaModes::PRIVATE_SCHEMA_MODE,
            ])) {
                return;
            }
            // Define the settings value through a hook. Execute last so it overrides the default settings
            $hookName = ComponentConfigurationHelpers::getHookName(
                AccessControlComponentConfiguration::class,
                AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE
            );
            \add_filter(
                $hookName,
                function () use ($defaultSchemaMode) {
                    return $defaultSchemaMode == SchemaModes::PRIVATE_SCHEMA_MODE;
                },
                PHP_INT_MAX
            );
        }
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
