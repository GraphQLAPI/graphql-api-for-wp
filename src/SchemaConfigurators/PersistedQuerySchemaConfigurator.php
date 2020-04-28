<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigCacheControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\PersistedQueryOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\CacheControlGraphQLQueryConfigurator;

class PersistedQuerySchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
    /**
     * Process the variables
     *
     * @return void
     */
    protected function executeOptionsSchemaConfiguration(int $customPostID): void
    {
        $optionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $customPostID,
            PluginState::getPersistedQueryOptionsBlock()
        );
        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        $acceptVariablesAsURLParams = $optionsBlockDataItem['attrs'][PersistedQueryOptionsBlock::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? true;
        if ($acceptVariablesAsURLParams) {

        } else {

        }
    }
    /**
     * Apply all the settings defined in the Schema Configuration:
     * - Access Control Lists
     * - Cache Control Lists
     * - Field Deprecation Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationItems(int $schemaConfigurationID): void
    {
        parent::executeSchemaConfigurationItems($schemaConfigurationID);

        // Also execute the Cache Control
        $this->executeSchemaConfigurationCacheControlLists($schemaConfigurationID);
    }

    /**
     * Apply all the settings defined in the Schema Configuration for:
     * - Cache Control Lists
     *
     * @param integer $schemaConfigurationID
     * @return void
     */
    protected function executeSchemaConfigurationCacheControlLists(int $schemaConfigurationID): void
    {
        $schemaConfigCCLBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            PluginState::getSchemaConfigCacheControlListBlock()
        );
        if (!is_null($schemaConfigCCLBlockDataItem)) {
            if ($cacheControlLists = $schemaConfigCCLBlockDataItem['attrs'][SchemaConfigCacheControlListBlock::ATTRIBUTE_NAME_CACHE_CONTROL_LISTS]) {
                $configurator = new CacheControlGraphQLQueryConfigurator();
                foreach ($cacheControlLists as $cacheControlListID) {
                    $configurator->executeSchemaConfiguration($cacheControlListID);
                }
            }
        }
    }
}
