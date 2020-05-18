<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\SchemaConfigCacheControlListBlock;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\CacheControlGraphQLQueryConfigurator;

class PersistedQuerySchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
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

        // Also execute the Cache Control, unless previewing the query
        if (!\is_preview()) {
            $this->executeSchemaConfigurationCacheControlLists($schemaConfigurationID);
        }
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
        $instanceManager = InstanceManagerFacade::getInstance();
        $schemaConfigCCLBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $instanceManager->getInstance(SchemaConfigCacheControlListBlock::class)
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
