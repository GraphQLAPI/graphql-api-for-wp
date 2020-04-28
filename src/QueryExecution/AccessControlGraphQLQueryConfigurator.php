<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\QueryExecution;

use PoP\ComponentModel\Misc\GeneralUtils;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use PoP\AccessControl\Facades\AccessControlManagerFacade;

class AccessControlGraphQLQueryConfigurator extends AbstractIndividualControlGraphQLQueryConfigurator
{
    // protected function doInit(): void
    // {
    //     $this->setAccessControlList();
    // }

    /**
     * Extract the access control items defined in the CPT,
     * and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    public function executeConfiguration(int $aclPostID)
    {
        $aclBlockItems = BlockHelpers::getBlocksOfTypeFromCustomPost(
            $aclPostID,
            PluginState::getAccessControlBlock()
        );
        $accessControlManager = AccessControlManagerFacade::getInstance();
        // The "Access Control" type contains the fields/directives
        foreach ($aclBlockItems as $aclBlockItem) {
            // The rule to apply is contained inside the nested blocks
            if ($aclBlockItemNestedBlocks = $aclBlockItem['innerBlocks']) {
                $aclBlockItemTypeFields = $aclBlockItem['attrs']['typeFields'] ?? [];
                $aclBlockItemDirectives = $aclBlockItem['attrs']['directives'] ?? [];

                // The value can be NULL, then it's the default mode
                // In that case do nothing, since the default mode is already injected into GraphQL by PoP
                $schemaMode = $aclBlockItem['attrs']['schemaMode'];

                // Iterate all the nested blocks
                foreach ($aclBlockItemNestedBlocks as $aclBlockItemNestedBlock) {
                    if ($accessControlGroup = $aclBlockItemNestedBlock['attrs']['accessControlGroup']) {
                        // The value can be NULL, it depends on the actual nestedBlock
                        // (eg: Disable access doesn't have any, while Disable by role has the list of roles)
                        $value = $aclBlockItemNestedBlock['attrs']['value'];

                        // Extract the saved fields
                        if ($entriesForFields = array_filter(
                            array_map(
                                function ($selectedField) use ($value, $schemaMode) {
                                    return $this->getIndividualControlEntryFromField(
                                        $selectedField,
                                        $value,
                                        $schemaMode
                                    );
                                },
                                $aclBlockItemTypeFields
                            )
                        )) {
                            $accessControlManager->addEntriesForFields(
                                $accessControlGroup,
                                $entriesForFields
                            );
                        }

                        // Extract the saved directives
                        if ($entriesForDirectives = GeneralUtils::arrayFlatten(array_filter(
                            array_map(
                                function ($selectedDirective) use ($value, $schemaMode) {
                                    return $this->getIndividualControlEntriesFromDirective(
                                        $selectedDirective,
                                        $value,
                                        $schemaMode
                                    );
                                },
                                $aclBlockItemDirectives
                            )
                        ))) {
                            $accessControlManager->addEntriesForDirectives(
                                $accessControlGroup,
                                $entriesForDirectives
                            );
                        }
                    }
                }
            }
        }
    }
}
