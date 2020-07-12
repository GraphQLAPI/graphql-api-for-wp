<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfigurators;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\HookHelpers;
use GraphQLAPI\GraphQLAPI\General\BlockHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Blocks\FieldDeprecationBlock;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
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
        if (!$moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::FIELD_DEPRECATION)) {
            return;
        }

        $instanceManager = InstanceManagerFacade::getInstance();
        $fdlBlockItems = BlockHelpers::getBlocksOfTypeFromCustomPost(
            $fdlPostID,
            $instanceManager->getInstance(FieldDeprecationBlock::class)
        );
        // $fieldDeprecationManager = FieldDeprecationManagerFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        $hooksAPI = HooksAPIFacade::getInstance();
        foreach ($fdlBlockItems as $fdlBlockItem) {
            if ($deprecationReason = $fdlBlockItem['attrs'][FieldDeprecationBlock::ATTRIBUTE_NAME_DEPRECATION_REASON]) {
                if ($typeFields = $fdlBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS]) {
                    // // Extract the saved fields
                    // if ($entriesForFields = GeneralUtils::arrayFlatten(
                    //     array_map(
                    //         function ($selectedField) use ($instanceManager, $deprecationReason) {
                    //             $entriesFromField = $this->getEntriesFromField($selectedField, $deprecationReason);
                    //             $entries = [];
                    //             foreach ($entriesFromField as $entry) {
                    //                 // Once getting the entry, we an obtain the type and field,
                    //                 // and we can modify the deprecated reason in the entry adding this information
                    //                 $typeResolverClass = $entry[0];
                    //                 // If we had a module (eg: "Users") and saved an entry with it,
                    //                 // and then disable it, the typeResolveClass will be null
                    //                 if (is_null($typeResolverClass)) {
                    //                     continue;
                    //                 }
                    //                 $typeResolver = $instanceManager->getInstance($typeResolverClass);
                    //                 $entry[2] = sprintf(
                    //                     \__('Field \'%1$s\' from type \'%2$s\' has been deprecated: %3$s'),
                    //                     $entry[1],
                    //                     $typeResolver->getMaybeNamespacedTypeName(),
                    //                     $entry[2]
                    //                 );
                    //                 $entries[] = $entry;
                    //             }
                    //             return $entries;
                    //         },
                    //         $typeFields
                    //     )
                    // )) {
                    //     $fieldDeprecationManager->addEntriesForFields(
                    //         $entriesForFields
                    //     );
                    // }
                    // Add a hook to override the schema for the selected fields
                    foreach ($typeFields as $selectedField) {
                        $entriesFromField = $this->getEntriesFromField($selectedField, $deprecationReason);
                        foreach ($entriesFromField as $entry) {
                            // Once getting the entry, we an obtain the type and field,
                            // and we can modify the deprecated reason in the entry adding this information
                            $typeResolverClass = $entry[0];
                            // If we had a module (eg: "Users") and saved an entry with it,
                            // and then disable it, the typeResolveClass will be null
                            if (is_null($typeResolverClass)) {
                                continue;
                            }
                            $fieldName = $entry[1];
                            $typeResolver = $instanceManager->getInstance($typeResolverClass);
                            $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
                                $typeResolver,
                                $fieldName
                            );
                            $hooksAPI->addFilter(
                                $hookName,
                                function (array $schemaDefinition) use ($typeResolver, $fieldName, $deprecationReason): array {
                                    $deprecationDescription = sprintf(
                                        \__('Field \'%1$s\' from type \'%2$s\' has been deprecated: %3$s'),
                                        $fieldName,
                                        $typeResolver->getMaybeNamespacedTypeName(),
                                        $deprecationReason
                                    );
                                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                                    $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
                                    return $schemaDefinition;
                                },
                                10,
                                1
                            );
                        }
                    }
                }
            }
        }
    }
}
