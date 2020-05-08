<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EndpointResolvers;

use PoP\Routing\RouteNatures;
use PoP\API\Schema\QueryInputs;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\GraphQLAPIRequest\Hooks\VarsHooks;

trait EndpointResolverTrait
{
    /**
     * Execute the GraphQL query
     *
     * @return void
     */
    protected function executeGraphQLQuery(): void
    {
        /**
         * Priority 1: Execute before VarsHooks in the API package, to set-up the variables
         * in $vars as soon as we knows if it's a singular post of this type.
         * But after setting $vars['routing-state']['queried-object-id'], to get the current
         * post ID from $vars instead of the global context
         */
        \add_action(
            'ApplicationState:addVars',
            [$this, 'addGraphQLVars'],
            1,
            1
        );
        /**
         * Assign the single endpoint
         */
        \add_filter(
            'WPCMSRoutingState:nature',
            [$this, 'getNature'],
            10,
            2
        );
    }

    /**
     * Assign the single endpoint by setting it as the Home nature
     */
    public function getNature($nature, $query)
    {
        return RouteNatures::HOME;
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return array Array of 2 elements: [query, variables]
     */
    abstract protected function getGraphQLQueryAndVariables($graphQLQueryPost): array;

    /**
     * Indicate if the GraphQL variables must override the URL params
     *
     * @return boolean
     */
    protected function doURLParamsOverrideGraphQLVariables($postOrID): bool
    {
        return false;
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @return void
     */
    public function addGraphQLVars($vars_in_array): void
    {
        /**
         * Remove any query passed through the request, to avoid users executing a custom query,
         * bypassing the persisted one
         */
        unset($_REQUEST[QueryInputs::QUERY]);

        // Indicate it is an API, of type GraphQL. Just by doing is, class
        // \PoP\GraphQLAPIRequest\Hooks\VarsHooks will process the GraphQL request
        $vars = &$vars_in_array[0];
        $vars['scheme'] = \POP_SCHEME_API;
        $vars['datastructure'] = GraphQLDataStructureFormatter::getName();

        /**
         * Get the query and variables from the implementing class
         */
        list(
            $graphQLQuery,
            $graphQLVariables
        ) = $this->getGraphQLQueryAndVariables($vars['routing-state']['queried-object']);
        if (!$graphQLQuery) {
            // If there is no query, nothing to do!
            return;
        }
        /**
         * Merge the variables into $vars
         */
        if ($graphQLVariables) {
            // Normally, GraphQL variables must not override the variables from the request
            // But this behavior can be overriden for the persisted query,
            // by setting "Accept Variables as URL Params" => false
            $vars['variables'] = $this->doURLParamsOverrideGraphQLVariables($vars['routing-state']['queried-object-id']) ?
                array_merge(
                    $graphQLVariables,
                    $vars['variables'] ?? []
                ) :
                array_merge(
                    $vars['variables'] ?? [],
                    $graphQLVariables
                );
        }
        // Add the query into $vars
        $instanceManager = InstanceManagerFacade::getInstance();
        $graphQLAPIRequestHookSet = $instanceManager->getInstance(VarsHooks::class);
        $graphQLAPIRequestHookSet->addGraphQLQueryToVars($vars, $graphQLQuery);
    }
}
