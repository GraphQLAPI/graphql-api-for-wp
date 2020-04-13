<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\General;

use Leoloso\GraphQLByPoPWPPlugin\General\GraphiQLBlockHelpers;

class GraphQLQueryPostTypeHelpers
{

    /**
     * A GraphQL Query Custom Post Type is hierarchical: each query post can have a parent, enabling to fetch attributes from the parent post
     * If a GraphiQL block has not defined a query or variables, or the CPT post has not defined its access control list or cache control list,
     * then these attributes are retrieved from the parent, until all attributes have a value.
     *
     * This enables to implement strategies for different GraphQL query hierarchies, for instance:
     *
     * 1. Define root queries called "MobileApp" and "Website", with their corresponding ACL/CCL, and have the actual GraphQL queries inherit from them
     * 2. Define a root GraphQL query without variables, and extend with posts "MobileApp" and "Website" with different variables, eg: changing the value for `$limit`
     *
     * @param [type] $graphQLQueryPost
     * @param bool $inheritAttributes Indicate if to iterate towards the parent of the GraphQL query post to fetch the missing attributes
     * @return array array with 4 elements: [$graphQLQuery, $graphQLVariables, $acl, $ccl]
     */
    public static function getGraphQLQueryPostAttributes($graphQLQueryPost, bool $inheritAttributes): array
    {
        /**
         * Obtain the attributes from the block. As long as any of them is empty, and the GraphQL query post still has a parent,
         * attempt to fetch those missing attributes from it
         */
        $graphQLQuery = $graphQLVariables = null;
        while (!is_null($graphQLQueryPost)) {
            list(
                $postGraphQLQuery,
                $postGraphQLVariables
            ) = GraphiQLBlockHelpers::getSingleGraphiQLBlockAttributesFromPost($graphQLQueryPost);
            if (!$graphQLQuery) {
                $graphQLQuery = $postGraphQLQuery;
            }
            if (!$graphQLVariables) {
                $graphQLVariables = $postGraphQLVariables;
            }
            // If any of them is still empty, and this post has a parent, then load it for the next iteration
            if ($inheritAttributes && (!$graphQLQuery || !$graphQLVariables) && $graphQLQueryPost->post_parent) {
                $graphQLQueryPost = \get_post($graphQLQueryPost->post_parent);
            } else {
                // Otherwise, finish iterating
                $graphQLQueryPost = null;
            }
        }
        return [
            $graphQLQuery,
            $graphQLVariables,
        ];
    }
}
