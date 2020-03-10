<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Exception;
use Leoloso\GraphQLByPoPWPPlugin\General\RequestParams;
use PoP\Routing\RouteNatures;
use PoP\API\Schema\QueryInputs;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\CacheControl\Facades\CacheControlEngineFacade;
use PoP\CacheControl\Environment as CacheControlEnvironment;

class GraphQLQueryPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-query';
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
        return array_merge(
            parent::getPostTypeArgs(),
            [
                'hierarchical' => true,
                'supports' => [
                    'title',
                    'editor',
                    'author',
                    'excerpt',
                    'revisions',
                    'custom-fields',
                    'page-attributes',
                ],
                'taxonomies' => [
                    self::TAXONOMY_CATEGORY,
                ],
                'show_in_menu' => true,
                'show_in_admin_bar' => true,
            ]
        );
    }

    /**
     * Initialize the different post types
     *
     * @return void
     */
    public function initPostType(): void
    {
        // First install the taxonomy
        $this->installTaxonomies();
        parent::initPostType();
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
     * Add the hook to initialize the different post types
     *
     * @return void
     */
    public function init(): void
    {
        parent::init();

        /**
         * 2 outputs:
         * 1. Resolution of the GraphQL API, by default
         * 2. Documentation for the GraphQL API, when passing ?view=source
         */
        if ($_REQUEST[RequestParams::VIEW] != RequestParams::VIEW_SOURCE) {
            /**
             * Execute first, before VarsHooks in the API package, to set-up the variables in $vars as soon as we knows if it's a singular post of this type
             */
            \add_action(
                '\PoP\ComponentModel\Engine_Vars:addVars',
                [$this, 'addVars'],
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
                [$this, 'setSourceContent']
            );
        }
    }

    /**
     * Add the excerpt (if not empty), which is the description of the GraphQL query
     *
     * @param [type] $content
     * @return string
     */
    public function setSourceContent($content): string
    {
        global $post;
        if ($excerpt = $post->post_excerpt) {
            $content = \sprintf(
                \__('<p><strong>Description: </strong>%s</p>'),
                $excerpt
            ).$content;
        }
        // Also prettyprint the code
        $content .= \sprintf('<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>');
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
    public function addVars($vars_in_array)
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
             * Extract the query from the post, and set it in $vars
             */
            global $post;
            $blocks = \parse_blocks($post->post_content);
            // There must be only one block of type GraphiQL. Fetch it
            $graphiQLBlock = PluginState::getGraphiQLBlock();
            $graphiqlBlocks = array_filter(
                $blocks,
                function($block) use($graphiQLBlock) {
                    return $block['blockName'] == $graphiQLBlock->getBlockFullName();
                }
            );
            if (count($graphiqlBlocks) != 1) {
                throw new Exception(
                    \__('This GraphQL query has corrupted content, so it can\'t be processed.', 'graphql-by-pop')
                );
            }
            $graphiqlBlock = $graphiqlBlocks[0];
            if ($graphQLQuery = $graphiqlBlock['attrs']['query']) {
                $variables = $graphiqlBlock['attrs']['variables'];
                if ($variables) {
                    // Variables is saved as a string, convert to array
                    $variables = json_decode($variables, true);
                    // There may already be variables from the request, which must override any fixed variable stored in the query
                    $vars['variables'] = array_merge(
                        $variables,
                        $vars['variables'] ?? []
                    );
                }
                $graphQLAPIRequestHookSet->addGraphQLQueryToVars($vars, $graphQLQuery);
            }
        }
    }
}
