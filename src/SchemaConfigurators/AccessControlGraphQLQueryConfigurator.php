<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use PoP\ComponentModel\Misc\GeneralUtils;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractControlBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlRuleBlocks\AbstractAccessControlRuleBlock;

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
    public function executeSchemaConfiguration(int $aclPostID): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $aclBlockItems = BlockHelpers::getBlocksOfTypeFromCustomPost(
            $aclPostID,
            $instanceManager->getInstance(AccessControlBlock::class)
        );
        $accessControlManager = AccessControlManagerFacade::getInstance();
        // The "Access Control" type contains the fields/directives
        foreach ($aclBlockItems as $aclBlockItem) {
            // The rule to apply is contained inside the nested blocks
            if ($aclBlockItemNestedBlocks = $aclBlockItem['innerBlocks']) {
                $aclBlockItemTypeFields = $aclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_TYPE_FIELDS] ?? [];
                $aclBlockItemDirectives = $aclBlockItem['attrs'][AbstractControlBlock::ATTRIBUTE_NAME_DIRECTIVES] ?? [];

                // The value can be NULL, then it's the default mode
                // In that case do nothing, since the default mode is already injected into GraphQL by PoP
                $schemaMode = $aclBlockItem['attrs'][AccessControlBlock::ATTRIBUTE_NAME_SCHEMA_MODE];

                // Iterate all the nested blocks
                foreach ($aclBlockItemNestedBlocks as $aclBlockItemNestedBlock) {
                    if ($accessControlGroup = $aclBlockItemNestedBlock['attrs'][AbstractAccessControlRuleBlock::ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP]) {
                        // The value can be NULL, it depends on the actual nestedBlock
                        // (eg: Disable access doesn't have any, while Disable by role has the list of roles)
                        $value = $aclBlockItemNestedBlock['attrs'][AbstractAccessControlRuleBlock::ATTRIBUTE_NAME_VALUE];

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
