<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use PoP\Routing\RouteNatures;
use PoP\API\Schema\QueryInputs;
use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;

abstract class AbstractGraphQLQueryExecutionPostType extends AbstractPostType
{
    /**
     * Indicates if we executing the GraphQL query (`true`) or doing something else
     * (such as visualizing the query source)
     *
     * @return boolean
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return true;
    }

    /**
     * Add the hook to initialize the different post types
     *
     * @return void
     */
    public function init(): void
    {
        parent::init();

        /**
         * Two outputs:
         * 1.`isGraphQLQueryExecution` = true, then resolve the GraphQL query
         * 2.`isGraphQLQueryExecution` = false, then do something else (eg: view the source for the GraphQL query)
         */
        if ($this->isGraphQLQueryExecution()) {
            $this->executeGraphQLQuery();
        } else {
            $this->doSomethingElse();
        }
    }

    /**
     * Execute the GraphQL query
     *
     * @return void
     */
    protected function executeGraphQLQuery(): void
    {
        /**
         * Execute first, before VarsHooks in the API package, to set-up the variables in $vars
         * as soon as we knows if it's a singular post of this type
         */
        \add_action(
            'ApplicationState:addVars',
            [$this, 'addGraphQLVars'],
            0,
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
        // /**
        //  * Manage Cache Control
        //  */
        // \add_action(
        //     'popcms:boot',
        //     [$this, 'manageCacheControl']
        // );
    }

    /**
     * Do something else, not the execution of the GraphQL query
     *
     * @return void
     */
    protected function doSomethingElse(): void
    {
        // By default, do nothing
    }

    // /**
    //  * Disable Cache Control when previewing the new GraphQL query
    //  */
    // public function manageCacheControl()
    // {
    //     // If cache control enabled and it is a preview of the GraphQL query...
    //     if (!CacheControlEnvironment::disableCacheControl() && \is_singular($this->getPostType()) && \is_preview()) {
    //         // Disable cache control by setting maxAge => 0
    //         $cacheControlEngine = CacheControlEngineFacade::getInstance();
    //         $cacheControlEngine->addMaxAge(0);
    //     }
    // }

    /**
     * Assign the single endpoint by setting it as the Home nature
     */
    public function getNature($nature, $query)
    {
        if ($query->is_singular($this->getPostType())) {
            return RouteNatures::HOME;
        }

        return $nature;
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return array Array of 2 elements: [query, variables]
     */
    abstract protected function getGraphQLQueryAndVariables(): array;

    abstract protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock;

    /**
     * Read the options block and check the value of attribute "isEnabled"
     *
     * @return void
     */
    protected function isEnabled(): bool
    {
        global $post;
        $optionsBlockDataItem = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $post->ID,
            $this->getQueryExecutionOptionsBlock()
        );
        // If there was no options block, something went wrong in the post content
        if (is_null($optionsBlockDataItem)) {
            return false;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][AbstractQueryExecutionOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED] ?? true;
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @return void
     */
    public function addGraphQLVars($vars_in_array): void
    {
        if (\is_singular($this->getPostType())) {
            // Check if it is enabled, by configuration
            if (!$this->isEnabled()) {
                return;
            }
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
            ) = $this->getGraphQLQueryAndVariables();
            if (!$graphQLQuery) {
                // If there is no query, nothing to do!
                return;
            }
            /**
             * Merge the variables into $vars
             */
            if ($graphQLVariables) {
                // There may already be variables from the request, which must override
                // any fixed variable stored in the query
                $vars['variables'] = array_merge(
                    $graphQLVariables,
                    $vars['variables'] ?? []
                );
            }
            // Add the query into $vars
            $instanceManager = InstanceManagerFacade::getInstance();
            $graphQLAPIRequestHookSet = $instanceManager->getInstance(\PoP\GraphQLAPIRequest\Hooks\VarsHooks::class);
            $graphQLAPIRequestHookSet->addGraphQLQueryToVars($vars, $graphQLQuery);
        }
    }
}
