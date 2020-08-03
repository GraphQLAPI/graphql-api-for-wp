<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

use PoP\ComponentModel\Misc\GeneralUtils;
use GraphQLAPI\GraphQLAPI\General\BlockHelpers;
use GraphQLAPI\GraphQLAPI\Blocks\CacheControlBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\CacheControl\Facades\CacheControlManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

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
        // Only execute for GET operations
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            return;
        }

        // Only if the module is not disabled
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::CACHE_CONTROL)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        $cclBlockItems = BlockHelpers::getBlocksOfTypeFromCustomPost(
            $cclPostID,
            $instanceManager->getInstance(CacheControlBlock::class)
        );
        $cacheControlManager = CacheControlManagerFacade::getInstance();
        // The "Cache Control" type contains the fields/directives and the max-age
        foreach ($cclBlockItems as $cclBlockItem) {
            $maxAge = $cclBlockItem['attrs'][CacheControlBlock::ATTRIBUTE_NAME_CACHE_CONTROL_MAX_AGE];
            if (!is_null($maxAge) && $maxAge >= 0) {
                // Extract the saved fields
                if ($typeFields = $cclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS]) {
                    if ($entriesForFields = GeneralUtils::arrayFlatten(
                        array_map(
                            function ($selectedField) use ($maxAge) {
                                return $this->getEntriesFromField($selectedField, $maxAge);
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
                if ($directives = $cclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_DIRECTIVES]) {
                    if ($entriesForDirectives = GeneralUtils::arrayFlatten(array_filter(
                        array_map(
                            function ($selectedDirective) use ($maxAge) {
                                return $this->getEntriesFromDirective($selectedDirective, $maxAge);
                            },
                            $directives
                        )
                    ))
                    ) {
                        $cacheControlManager->addEntriesForDirectives(
                            $entriesForDirectives
                        );
                    }
                }
            }
        }
    }
}
