<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use PoP\ComponentModel\Misc\GeneralUtils;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use PoP\CacheControl\Facades\CacheControlManagerFacade;

class CacheControlGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    /**
     * Extract the configuration items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    public function executeSchemaConfiguration(int $cclPostID): void
    {
        $cclBlockItems = BlockHelpers::getBlocksOfTypeFromCustomPost(
            $cclPostID,
            PluginState::getCacheControlBlock()
        );
        $cacheControlManager = CacheControlManagerFacade::getInstance();
        // The "Cache Control" type contains the fields/directives and the max-age
        foreach ($cclBlockItems as $cclBlockItem) {
            $maxAge = $cclBlockItem['attrs']['cacheControlMaxAge'];
            if (!is_null($maxAge) && $maxAge >= 0) {
                // Extract the saved fields
                if ($typeFields = $cclBlockItem['attrs']['typeFields']) {
                    if ($entriesForFields = array_filter(
                        array_map(
                            function ($selectedField) use ($maxAge) {
                                return $this->getEntryFromField($selectedField, $maxAge);
                            },
                            $typeFields
                        )
                    )) {
                        $cacheControlManager->addEntriesForFields(
                            $entriesForFields
                        );
                    }
                }

                // Extract the saved directives
                if ($directives = $cclBlockItem['attrs']['directives']) {
                    if ($entriesForDirectives = GeneralUtils::arrayFlatten(array_filter(
                        array_map(
                            function ($selectedDirective) use ($maxAge) {
                                return $this->getEntriesFromDirective($selectedDirective, $maxAge);
                            },
                            $directives
                        )
                    ))) {
                        $cacheControlManager->addEntriesForDirectives(
                            $entriesForDirectives
                        );
                    }
                }
            }
        }
    }
}
