<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\General\BlockHelpers;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Blocks\FieldDeprecationBlock;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\FieldDeprecationByDirective\Facades\FieldDeprecationManagerFacade;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\AbstractGraphQLQueryConfigurator;

class FieldDeprecationGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    /**
     * Extract the configuration items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    public function executeSchemaConfiguration($fdlPostID): void
    {
        // Only if the module is not disabled
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(ModuleResolver::FIELD_DEPRECATION)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        $fdlBlockItems = BlockHelpers::getBlocksOfTypeFromCustomPost(
            $fdlPostID,
            $instanceManager->getInstance(FieldDeprecationBlock::class)
        );
        $fieldDeprecationManager = FieldDeprecationManagerFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        foreach ($fdlBlockItems as $fdlBlockItem) {
            if ($deprecationReason = $fdlBlockItem['attrs'][FieldDeprecationBlock::ATTRIBUTE_NAME_DEPRECATION_REASON]) {
                // Extract the saved fields
                if ($typeFields = $fdlBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS]) {
                    if (
                        $entriesForFields = array_filter(
                            array_map(
                                function ($selectedField) use ($instanceManager, $deprecationReason) {
                                    $entry = $this->getEntryFromField($selectedField, $deprecationReason);
                                    // Once getting the entry, we an obtain the type and field,
                                    // and we can modify the deprecated reason in the entry adding this information
                                    $typeResolverClass = $entry[0];
                                    $typeResolver = $instanceManager->getInstance($typeResolverClass);
                                    $entry[2] = sprintf(
                                        \__('Field \'%1$s\' from type \'%2$s\' has been deprecated: %3$s'),
                                        $entry[1],
                                        $typeResolver->getMaybeNamespacedTypeName(),
                                        $entry[2]
                                    );
                                    return $entry;
                                },
                                $typeFields
                            )
                        )
                    ) {
                        $fieldDeprecationManager->addEntriesForFields(
                            $entriesForFields
                        );
                    }
                }
            }
        }
    }
}
