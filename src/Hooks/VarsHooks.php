<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Hooks;

use PoP\Routing\RouteNatures;
use PoP\Engine\Hooks\AbstractHookSet;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;

class VarsHooks extends AbstractHookSet
{
    protected function init()
    {
        // Implement immediately, before VarsHooks in API adds output=json
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            array($this, 'maybeRemoveVars'),
            0,
            1
        );
    }

    /**
     * If the single endpoint is disabled:
     * Do not allow to query the endpoint through URL, like ?scheme=api&datastructure=graphql
     */
    public function maybeRemoveVars($vars_in_array)
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if (!$moduleRegistry->isModuleEnabled(ModuleResolver::SINGLE_ENDPOINT)) {
            $vars = &$vars_in_array[0];
            if ($vars['scheme'] == \POP_SCHEME_API) {
                // Remove
                unset($vars['scheme']);
                unset($vars['datastructure']);
            }
        }
    }
}
