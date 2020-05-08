<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use Leoloso\GraphQLByPoPWPPlugin\Security\UserAuthorization;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\Taxonomies\GraphQLQueryTaxonomy;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\PersistedQueryOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\General\GraphQLQueryPostTypeHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractGraphQLQueryExecutionPostType;

class GraphQLPersistedQueryPostType extends AbstractGraphQLQueryExecutionPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-query';

    /**
     * Custom Post Type name
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Access endpoints under /graphql-query, or wherever it is configured to
     *
     * @return string|null
     */
    protected function getSlugBase(): ?string
    {
        return ComponentConfiguration::getPersistedQuerySlugBase();
    }

    /**
     * Custom post type name
     *
     * @return void
     */
    public function getPostTypeName(): string
    {
        return \__('GraphQL persisted query', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL persisted queries', 'graphql-api');
    }

    /**
     * Labels for registering the post type
     *
     * @param string $name_uc Singular name uppercase
     * @param string $names_uc Plural name uppercase
     * @param string $names_lc Plural name lowercase
     * @return array
     */
    protected function getPostTypeLabels(string $name_uc, string $names_uc, string $names_lc): array
    {
        /**
         * Because the name is too long, shorten it for the admin menu only
         */
        return array_merge(
            parent::getPostTypeLabels($name_uc, $names_uc, $names_lc),
            array(
                'all_items' => \__('Persisted queries', 'graphql-api'),
            )
        );
    }

    /**
     * The Query is publicly accessible, and the permalink must be configurable
     *
     * @return boolean
     */
    protected function isPublic(): bool
    {
        return true;
    }

    /**
     * Taxonomies
     *
     * @return array
     */
    protected function getTaxonomies(): array
    {
        return [
            GraphQLQueryTaxonomy::TAXONOMY_CATEGORY,
        ];
    }

    /**
     * Hierarchical
     *
     * @return bool
     */
    protected function isHierarchical(): bool
    {
        return true;
    }

    // /**
    //  * Show in admin bar
    //  *
    //  * @return bool
    //  */
    // protected function showInAdminBar(): bool
    // {
    //     return true;
    // }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergTemplate(): array
    {
        $graphiQLBlock = PluginState::getGraphiQLBlock();
        $schemaConfigurationBlock = PluginState::getSchemaConfigurationBlock();
        $persistedQueryOptionsBlock = PluginState::getPersistedQueryOptionsBlock();
        return [
            [$graphiQLBlock->getBlockFullName()],
            [$schemaConfigurationBlock->getBlockFullName()],
            [$persistedQueryOptionsBlock->getBlockFullName()],
        ];
    }

    /**
     * Indicates if to lock the Gutenberg templates
     *
     * @return boolean
     */
    protected function lockGutenbergTemplate(): bool
    {
        return true;
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Add the parent query to the rendering of the GraphQL Query CPT
     *
     * @param Object $graphQLQueryPost
     * @return string
     */
    protected function getGraphQLQuerySourceContent(string $content, $graphQLQueryPost): string
    {
        $content = parent::getGraphQLQuerySourceContent($content, $graphQLQueryPost);

        /**
         * If the GraphQL query has a parent, possibly it is missing the query/variables/acl/ccl attributes,
         * which inherits from some parent
         * In that case, render the block twice:
         * 1. The current block, with missing attributes
         * 2. The final block, completing the missing attributes from its parent
         */
        if ($graphQLQueryPost->post_parent) {
            // Check if the user is authorized to see the content
            if (UserAuthorization::canAccessSchemaEditor()) {
                // Check if any attribute is missing
                list(
                    $graphQLQuery,
                    $graphQLVariables
                ) = GraphQLQueryPostTypeHelpers::getGraphQLQueryPostAttributes($graphQLQueryPost, false);
                // To render the variables in the block, they must be json_encoded
                if ($graphQLVariables) {
                    $graphQLVariables = json_encode($graphQLVariables);
                }
                if (!$graphQLQuery || !$graphQLVariables) {
                    // Fetch the attributes using inheritance
                    list(
                        $inheritedGraphQLQuery,
                        $inheritedGraphQLVariables
                    ) = GraphQLQueryPostTypeHelpers::getGraphQLQueryPostAttributes($graphQLQueryPost, true);
                    if ($inheritedGraphQLVariables) {
                        $inheritedGraphQLVariables = json_encode($inheritedGraphQLVariables);
                    }
                    // If the 2 sets of attributes are different, then render the block again
                    if (
                        $graphQLQuery != $inheritedGraphQLQuery
                        || $graphQLVariables != $inheritedGraphQLVariables
                    ) {
                        // Render the block again, using the inherited attributes
                        $inheritedGraphQLBlockAttributes = [
                            GraphiQLBlock::ATTRIBUTE_NAME_QUERY => $inheritedGraphQLQuery,
                            GraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $inheritedGraphQLVariables,
                        ];
                        // Add the new rendering to the output, and a description for each
                        $graphiQLBlock = PluginState::getGraphiQLBlock();
                        $ancestorContent = $graphiQLBlock->renderBlock($inheritedGraphQLBlockAttributes, '');
                    }
                }
            } else {
                $graphiQLBlock = PluginState::getGraphiQLBlock();
                $ancestorContent = $graphiQLBlock->renderUnauthorizedAccess();
            }
            if ($ancestorContent) {
                $content = sprintf(
                    '%s%s<hr/>%s%s',
                    \__('<p><u>Executable GraphQL query (with inherited properties): </u></p>'),
                    $ancestorContent,
                    \__('<p><u>Defined GraphQL query (without inherited properties): </u></p>'),
                    $content
                );
            }
        }

        return $content;
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return array
     */
    protected function getGraphQLQueryAndVariables($graphQLQueryPost): array
    {
        /**
         * Extract the query from the post (or from its parents), and set it in $vars
         */
        return GraphQLQueryPostTypeHelpers::getGraphQLQueryPostAttributes($graphQLQueryPost, true);
    }

    protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock
    {
        return PluginState::getPersistedQueryOptionsBlock();
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     *
     * @return boolean
     */
    protected function doURLParamsOverrideGraphQLVariables($postOrID): bool
    {
        $optionsBlockDataItem = $this->getOptionsBlockDataItem($postOrID);

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][PersistedQueryOptionsBlock::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? true;
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
            $vars = &$vars_in_array[0];
            if (!$this->isEnabled($vars['routing-state']['queried-object-id'])) {
                return;
            }
            // Remove the VarsHooks from the GraphQLAPIRequest, so it doesn't process the GraphQL query
            // Otherwise it will add error "The query in the body is empty"
            $instanceManager = InstanceManagerFacade::getInstance();
            $graphQLAPIRequestHookSet = $instanceManager->getInstance(\PoP\GraphQLAPIRequest\Hooks\VarsHooks::class);
            \remove_action(
                'ApplicationState:addVars',
                array($graphQLAPIRequestHookSet, 'addURLParamVars'),
                20,
                1
            );

            // Execute the original logic
            parent::addGraphQLVars($vars_in_array);
        }
    }
}
