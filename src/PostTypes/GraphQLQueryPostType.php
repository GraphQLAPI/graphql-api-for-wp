<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Exception;
use PoP\Routing\RouteNatures;
use PoP\API\Schema\QueryInputs;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use PoP\CacheControl\Facades\CacheControlEngineFacade;
use Leoloso\GraphQLByPoPWPPlugin\General\RequestParams;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;
use PoP\CacheControl\Environment as CacheControlEnvironment;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use Leoloso\GraphQLByPoPWPPlugin\General\GraphQLQueryPostTypeHelpers;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;

class GraphQLQueryPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-query';
    /**
     * "Category" taxonomy
     */
    public const TAXONOMY_CATEGORY = 'graphql-category';

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
     * Custom post type name
     *
     * @return void
     */
    public function getPostTypeName(): string
    {
        return \__('GraphQL query', 'graphql-by-pop');
    }

    /**
     * Custom Post Type plural name
     *
     * @param boolean $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL queries', 'graphql-by-pop');
    }

    /**
     * Arguments for registering the post type
     *
     * @return array
     */
    protected function getPostTypeArgs(): array
    {
        $postTypeArgs = parent::getPostTypeArgs();
        $postTypeArgs['supports'][] = 'page-attributes';
        return array_merge(
            $postTypeArgs,
            [
                'hierarchical' => true,
                'taxonomies' => [
                    self::TAXONOMY_CATEGORY,
                ],
                'show_in_admin_bar' => true,
            ]
        );
    }

    /**
     * Install the "Category" taxonomy
     *
     * @return void
     */
    protected function installTaxonomies(): void
    {
        $labels = $this->getTaxonomyLabels(
            \__('Category', 'graphql-by-pop'),
            \__('Categories', 'graphql-by-pop'),
            \__('category', 'graphql-by-pop'),
            \__('categories', 'graphql-by-pop')
        );
        $args = array(
            'label' => \__('Categories', 'graphql-by-pop'),
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_rest' => true,
        );
        \register_taxonomy(
            self::TAXONOMY_CATEGORY,
            $this->getPostType(),
            $args
        );
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergFixedTemplates(): array
    {
        $graphiQLBlock = PluginState::getGraphiQLBlock();
        return [
            [$graphiQLBlock->getBlockFullName()],
        ];
    }

    /**
     * Indicates if we executing the GraphQL query (`true`) or visualizing the query source (`false`)
     * It returns always `true`, unless passing ?view=source in the single post URL
     *
     * @return boolean
     */
     protected function resolveGraphQLQuery(): bool
    {
        return $_REQUEST[RequestParams::VIEW] != RequestParams::VIEW_SOURCE;
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
         * 2 outputs:
         *
         * - `resolveGraphQLQuery` = true, then resolve the GraphQL query
         * - `resolveGraphQLQuery` = false, then view the source for the GraphQL query
         */
        if ($this->resolveGraphQLQuery()) {
            /**
             * Execute first, before VarsHooks in the API package, to set-up the variables in $vars as soon as we knows if it's a singular post of this type
             */
            \add_action(
                '\PoP\ComponentModel\Engine_Vars:addVars',
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
            /**
             * Manage Cache Control
             */
            \add_action(
                'popcms:boot',
                [$this, 'manageCacheControl']
            );
        } else {
            /** Add the excerpt, which is the description of the GraphQL query */
            \add_filter(
                'the_content',
                [$this, 'setGraphQLQuerySourceContent']
            );
        }
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
     * Render the GraphQL Query CPT
     *
     * @param [type] $content
     * @return string
     */
    public function setGraphQLQuerySourceContent($content): string
    {
        /**
         * Check if it is this CPT...
         */
        if (\is_singular($this->getPostType())) {
            global $post;
            /**
             * If the GraphQL query has a parent, possibly it is missing the query/variables/acl/ccl attributes, which inherits from some parent
             * In that case, render the block twice:
             * 1. The current block, with missing attributes
             * 2. The final block, completing the missing attributes from its parent
             */
            $graphQLQueryPost = $post;
            if ($graphQLQueryPost->post_parent) {
                // Check if any attribute is missing
                list(
                    $graphQLQuery,
                    $graphQLVariables
                ) = GraphQLQueryPostTypeHelpers::getGraphQLQueryPostAttributes($graphQLQueryPost, false);
                if (!$graphQLQuery || !$graphQLVariables) {
                    // Fetch the attributes using inheritance
                    list(
                        $inheritedGraphQLQuery,
                        $inheritedGraphQLVariables
                    ) = GraphQLQueryPostTypeHelpers::getGraphQLQueryPostAttributes($graphQLQueryPost, true);
                    // If the 2 sets of attributes are different, then render the block again
                    if (
                        ($graphQLQuery != $inheritedGraphQLQuery) ||
                        ($graphQLVariables != $inheritedGraphQLVariables)
                    ) {
                        // Render the block again, using the inherited attributes
                        $inheritedGraphQLBlockAttributes = [
                            'query' => $inheritedGraphQLQuery,
                            'variables' => $inheritedGraphQLVariables,
                        ];
                        // Add the new rendering to the output, and a description for each
                        $graphiQLBlock = PluginState::getGraphiQLBlock();
                        $content = sprintf(
                            '%s%s<hr/>%s%s',
                            \__('<p><u>Complete GraphQL query, with attributes inherited from parent(s): </u></p>'),
                            $graphiQLBlock->renderBlock($inheritedGraphQLBlockAttributes, ''),
                            \__('<p><u>Current GraphQL query, with missing attributes: </u></p>'),
                            $content
                        );
                    }
                }

                /**
                 * Prettyprint the code
                 */
                $content .= \sprintf('<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>');
            }
        }
        return $content;
    }

    /**
     * Disable Cache Control when previewing the new GraphQL query
     */
    public function manageCacheControl()
    {
        // If cache control enabled and it is a preview of the GraphQL query...
        if (!CacheControlEnvironment::disableCacheControl() && \is_singular($this->getPostType()) && \is_preview()) {
            // Disable cache control by setting maxAge => 0
            $cacheControlEngine = CacheControlEngineFacade::getInstance();
            $cacheControlEngine->addMaxAge(0);
        }
    }

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
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @return void
     */
    public function addGraphQLVars($vars_in_array)
    {
        if (\is_singular($this->getPostType())) {
            // Remove the VarsHooks from the GraphQLAPIRequest, so it doesn't process the GraphQL query
            // Otherwise it will add error "The query in the body is empty"
            $instanceManager = InstanceManagerFacade::getInstance();
            $graphQLAPIRequestHookSet = $instanceManager->getInstance(\PoP\GraphQLAPIRequest\Hooks\VarsHooks::class);
            \remove_action(
                '\PoP\ComponentModel\Engine_Vars:addVars',
                array($graphQLAPIRequestHookSet, 'addURLParamVars'),
                20,
                1
            );

            /**
             * Remove any query passed through the request, to avoid users executing a custom query, bypassing the persisted one
             */
            unset($_REQUEST[QueryInputs::QUERY]);

            // Indicate it is an API, of type GraphQL
            $vars = &$vars_in_array[0];
            $vars['scheme'] = \POP_SCHEME_API;
            $vars['datastructure'] = GraphQLDataStructureFormatter::getName();

            /**
             * Extract the query from the post (or from its parents), and set it in $vars
             *
             */
            global $post;
            $graphQLQueryPost = $post;
            list(
                $graphQLQuery,
                $graphQLVariables
            ) = GraphQLQueryPostTypeHelpers::getGraphQLQueryPostAttributes($graphQLQueryPost, true);
            if (!$graphQLQuery) {
                throw new Exception(
                    \__('This GraphQL query either has no query defined, or it has corrupted content, so it can\'t be processed.', 'graphql-by-pop')
                );
            }
            /**
             * Merge the variables into $vars
             */
            if ($graphQLVariables) {
                // Variables is saved as a string, convert to array
                $graphQLVariables = json_decode($graphQLVariables, true);
                /**
                 * Watch out! If the variables have a wrong format, eg: with an additional trailing comma, such as this:
                 * {
                 *   "limit": 3,
                 * }
                 * Then doing `json_decode` will return NULL. In that case, do nothing or the application will fail
                 */
                if (!is_null($graphQLVariables)) {
                    // There may already be variables from the request, which must override any fixed variable stored in the query
                    $vars['variables'] = array_merge(
                        $graphQLVariables,
                        $vars['variables'] ?? []
                    );
                }
            }
            // Add the query into $vars
            $graphQLAPIRequestHookSet->addGraphQLQueryToVars($vars, $graphQLQuery);
        }
    }
}
