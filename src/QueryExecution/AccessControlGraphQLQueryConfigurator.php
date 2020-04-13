<?php
namespace Leoloso\GraphQLByPoPWPPlugin\QueryExecution;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use PoP\AccessControl\Facades\AccessControlManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

class AccessControlGraphQLQueryConfigurator extends AbstractGraphQLQueryConfigurator
{
    protected function doInit(): void
    {
        $this->setAccessControlList();
    }

    /**
     * Extract the access control items defined in the CPT, and inject them into the service as to take effect in the current GraphQL query
     *
     * @return void
     */
    protected function setAccessControlList()
    {
        // If we found an ACL, load its rules/restrictions
        if ($aclPostID = $this->getConfigurationCustomPostID('acl-post-id')) {
            $aclBlockItems = $this->getBlocksOfTypeFromConfigurationCustomPost($aclPostID, PluginState::getAccessControlBlock());
            $accessControlManager = AccessControlManagerFacade::getInstance();
            // The "Access Control" type contains the fields/directives
            foreach ($aclBlockItems as $aclBlockItem) {
                // The rule to apply is contained inside the nested blocks
                if ($aclBlockItemNestedBlocks = $aclBlockItem['innerBlocks']) {
                    $aclBlockItemTypeFields = $aclBlockItem['attrs']['typeFields'] ?? [];
                    $aclBlockItemDirectives = $aclBlockItem['attrs']['directives'] ?? [];
                    // Iterate all the nested blocks
                    foreach ($aclBlockItemNestedBlocks as $aclBlockItemNestedBlock) {
                        if ($accessControlGroup = $aclBlockItemNestedBlock['attrs']['accessControlGroup']) {
                            // The value can be NULL, it depends on the actual nestedBlock (eg: Disable access doesn't have any, while Disable by role has the list of roles)
                            $value = $aclBlockItemNestedBlock['attrs']['value'];

                            // Extract the saved fields
                            if ($entriesForFields = array_filter(
                                array_map(
                                    function ($selectedField) use ($value) {
                                        return $this->getEntryFromField($selectedField, $value);
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
                                    function ($selectedDirective) use ($value) {
                                        return $this->getEntriesFromDirective($selectedDirective, $value);
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
}
