<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Hooks;

use PoP\Engine\Hooks\AbstractHookSet;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\AccessControlGraphQLQueryConfigurator;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserRolesBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserStateBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlDisableAccessBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserCapabilitiesBlock;

class ACLRuleBlockHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(
            AccessControlGraphQLQueryConfigurator::HOOK_ACL_RULE_BLOCK_CLASS_MODULES,
            [$this, 'getACLRuleBlockClassModules']
        );
    }

    /**
     * Add the modules to check if an ACL Rule Block is enabled or not
     *
     * @param array $aclRuleBlockClassModules
     * @return array
     */
    public function getACLRuleBlockClassModules(array $blockClassModules): array
    {
        return array_merge(
            $blockClassModules,
            [
                AccessControlDisableAccessBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
                AccessControlUserStateBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_USER_STATE,
                AccessControlUserRolesBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_USER_ROLES,
                AccessControlUserCapabilitiesBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
            ]
        );
    }
}
