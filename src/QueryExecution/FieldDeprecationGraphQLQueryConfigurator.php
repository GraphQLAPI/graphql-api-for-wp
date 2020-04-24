<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\QueryExecution;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use PoP\FieldDeprecation\Facades\FieldDeprecationManagerFacade;

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
            foreach ($fdlBlockItems as $fdlBlockItem) {
                if ($deprecationReason = $fdlBlockItem['attrs']['deprecationReason']) {
                    // Extract the saved fields
                    if ($typeFields = $fdlBlockItem['attrs']['typeFields']) {
                        if ($entriesForFields = array_filter(
                            array_map(
                                function ($selectedField) use ($deprecationReason) {
                                    return $this->getEntryFromField($selectedField, $deprecationReason);
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
