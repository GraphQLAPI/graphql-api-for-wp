<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\General\BlockHelpers;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\EndpointResolvers\EndpointResolverTrait;

abstract class AbstractGraphQLQueryExecutionPostType extends AbstractPostType
{
    use EndpointResolverTrait {
        EndpointResolverTrait::getNature as getUpstreamNature;
        EndpointResolverTrait::addGraphQLVars as upstreamAddGraphQLVars;
    }
    
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
     * Do something else, not the execution of the GraphQL query
     *
     * @return void
     */
    protected function doSomethingElse(): void
    {
        // By default, do nothing
    }

    /**
     * Assign the single endpoint by setting it as the Home nature
     */
    public function getNature($nature, $query)
    {
        if ($query->is_singular($this->getPostType())) {
            return $this->getUpstreamNature($nature, $query);
        }

        return $nature;
    }

    abstract protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock;

    /**
     * Read the options block and check the value of attribute "isEnabled"
     *
     * @return void
     */
    protected function isEnabled(): bool
    {
        $optionsBlockDataItem = $this->getOptionsBlockDataItem();
        // If there was no options block, something went wrong in the post content
        if (is_null($optionsBlockDataItem)) {
            return false;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][AbstractQueryExecutionOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED] ?? true;
    }

    protected function getOptionsBlockDataItem(): ?array
    {
        global $post;
        return BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $post->ID,
            $this->getQueryExecutionOptionsBlock()
        );
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     *
     * @return boolean
     */
    protected function doURLParamsOverrideGraphQLVariables(): bool
    {
        return true;
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @return void
     */
    public function addGraphQLVars($vars_in_array): void
    {
        if (\is_singular($this->getPostType()) && $this->isEnabled()) {
            
            $this->upstreamAddGraphQLVars($vars_in_array);
        }
    }
}
