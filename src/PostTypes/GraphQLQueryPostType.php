<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;
use PoP\API\Schema\QueryInputs;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\Routing\RouteNatures;
use PoP\API\Schema\FieldQueryConvertorUtils;
use PoP\GraphQLAPIQuery\Facades\GraphQLQueryConvertorFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class GraphQLQueryPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-query';
    public const TAXONOMY = 'graphql-category';

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
    protected function getArgs(): array
    {
        return array_merge(
            parent::getArgs(),
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
                    self::TAXONOMY,
                    // 'category',
                ],
                'public' => true,
                'show_in_menu' => true,
                'show_in_admin_bar' => true,
            ]
        );
    }

    /**
     * Labels for registering the post type
     *
     * @return array
     */
    protected function getLabels(): array
    {
        /**
         * Placeholders for printing the different labels
         */
        $placeholder_parent_item_colon = \__('Parent %s:', 'graphql-by-pop');

        $name_uc = $this->getPostTypeName();
        return array_merge(
            parent::getLabels(),
            array(
                'parent_item_colon' => sprintf($placeholder_parent_item_colon, $name_uc),
            )
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
        $this->installTaxonomy();
        parent::initPostType();
        // \register_taxonomy_for_object_type('category', $this->getPostType());
    }

    protected function installTaxonomy()
    {
        $labels = array(
            'name'                           => \__('Categories', 'graphql-by-pop'),
            'singular_name'                  => \__('Category', 'graphql-by-pop'),
            'search_items'                   => \__('Search Categories', 'graphql-by-pop'),
            'all_items'                      => \__('All Categories', 'graphql-by-pop'),
            'edit_item'                      => \__('Edit Category', 'graphql-by-pop'),
            'update_item'                    => \__('Update Category', 'graphql-by-pop'),
            'add_new_item'                   => \__('Add New Category', 'graphql-by-pop'),
            'new_item_name'                  => \__('Add New Category', 'graphql-by-pop'),
            'menu_name'                      => \__('Category', 'graphql-by-pop'),
            'view_item'                      => \__('View Category', 'graphql-by-pop'),
            'popular_items'                  => \__('Popular categories', 'graphql-by-pop'),
            'separate_items_with_commas'     => \__('Separate categories with commas', 'graphql-by-pop'),
            'add_or_remove_items'            => \__('Add or remove category', 'graphql-by-pop'),
            'choose_from_most_used'          => \__('Choose from the most used categories', 'graphql-by-pop'),
            'not_found'                      => \__('No categories found', 'graphql-by-pop'),
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
            self::TAXONOMY,
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
        return [
            ['graphql-by-pop/graphiql'],
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
         * Execute first, before VarsHooks in the API package, to set-up the variables in $vars as soon as we knows if it's a singular post of this type
         */
        \add_action(
            '\PoP\ComponentModel\Engine_Vars:addVars',
            [$this, 'addVars'],
            0,
            1
        );

        \add_filter(
            'WPCMSRoutingState:nature',
            [$this, 'getNature'],
            10,
            2
        );
    }

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

            $vars = &$vars_in_array[0];

            // Indicate it is an API, of type GraphQL
            $vars['scheme'] = \POP_SCHEME_API;
            $vars['datastructure'] = GraphQLDataStructureFormatter::getName();

            /**
             * Remove any query passed through the request, to avoid users executing a custom query, bypassing the persisted one
             */
            unset($_REQUEST[QueryInputs::QUERY]);

            /**
             * Extract the query from the post, and set it in $vars
             */
            global $post;
            $blocks = \parse_blocks($post->post_content);
            // There must be only one block, of type GraphiQL
            // ...
            $graphiqlBlock = $blocks[0];
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
                $graphQLAPIRequestHookSet->addGraphQLQueryToVars($vars, $graphQLQuery, $variables);
            }
        }
    }
}
