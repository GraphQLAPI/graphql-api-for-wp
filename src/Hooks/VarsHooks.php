<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Hooks;

use PoP\Engine\Hooks\AbstractHookSet;

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
     * Do not allow to query the endpoint through URL, like ?scheme=api&datastructure=graphql
     */
    public function maybeRemoveVars($vars_in_array)
    {
        $vars = &$vars_in_array[0];
        if ($vars['scheme'] == \POP_SCHEME_API) {
            // Remove
            unset($vars['scheme']);
            unset($vars['datastructure']);
        }
    }
}
