<?php
namespace Leoloso\GraphQLByPoPWPPlugin\QueryExecution;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use PoP\AccessControl\Facades\AccessControlManagerFacade;

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
        global $post;
        $graphQLQueryPost = $post;
        do {
            $aclPostID = \get_post_meta($graphQLQueryPost->ID, 'acl-post-id', true);
            // If it doesn't have an ACL defined, and it has a parent, check if it has an ACL, then use that one
            if (!$aclPostID && $graphQLQueryPost->post_parent) {
                $graphQLQueryPost = \get_post($graphQLQueryPost->post_parent);
            } else {
                // Make sure to exit the `while` for the root post, even if it doesn't have ACL
                $graphQLQueryPost = null;
            }
        } while (!$aclPostID && !is_null($graphQLQueryPost));

        // If we found an ACL, load its rules/restrictions
        if ($aclPostID) {
            $aclPost = \get_post($aclPostID);
            $blocks = \parse_blocks($aclPost->post_content);
            // Obtain the blocks of "Access Control" type
            $aclBlock = PluginState::getAccessControlBlock();
            $aclBlockFullName = $aclBlock->getBlockFullName();
            $aclBlockItems = array_filter(
                $blocks,
                function($block) use($aclBlockFullName) {
                    return $block['blockName'] == $aclBlockFullName;
                }
            );
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
                                    function($selectedField) use($value) {
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
                            if ($entriesForDirectives = array_filter(
                                array_map(
                                    function($selectedDirective) use($value) {
                                        return $this->getEntryFromDirective($selectedDirective, $value);
                                    },
                                    $aclBlockItemDirectives
                                )
                            )) {
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
