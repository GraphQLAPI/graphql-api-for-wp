<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\QueryExecution;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use PoP\FieldDeprecationByDirective\Facades\FieldDeprecationManagerFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class FieldDeprecationGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    protected function doInit(): void
    {
        $this->setFieldDeprecationList();
    }

    /**
     * Extract the access control items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    protected function setFieldDeprecationList()
    {
        // If we found a CCL, load its rules/restrictions
        if ($fdlPostID = $this->getConfigurationCustomPostID('fdl-post-id')) {
            $fdlBlockItems = $this->getBlocksOfTypeFromConfigurationCustomPost(
                $fdlPostID,
                PluginState::getFieldDeprecationBlock()
            );
            $fieldDeprecationManager = FieldDeprecationManagerFacade::getInstance();
            $instanceManager = InstanceManagerFacade::getInstance();
            foreach ($fdlBlockItems as $fdlBlockItem) {
                if ($deprecationReason = $fdlBlockItem['attrs']['deprecationReason']) {
                    // Extract the saved fields
                    if ($typeFields = $fdlBlockItem['attrs']['typeFields']) {
                        if ($entriesForFields = array_filter(
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
                        )) {
                            $fieldDeprecationManager->addEntriesForFields(
                                $entriesForFields
                            );
                        }
                    }
                }
            }
        }
    }
}
