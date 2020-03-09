<?php
namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractPostType;
use Leoloso\PoPAPIEndpointsForWP\EndpointHandler;
use PoP\API\Schema\QueryInputs;
use PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\GraphQL\PersistedQueries\GraphQLPersistedQueryUtils;
use PoP\Routing\RouteNatures;
use PoP\API\Schema\FieldQueryConvertorUtils;
use PoP\GraphQLAPIQuery\Facades\GraphQLQueryConvertorFacade;

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
         * Execute first, to set-up the variables in $vars as soon as we knows if it's a singular post of this type
         */
        \add_action(
            'popcms:boot',
            [$this, 'maybeSetAPIRequest'],
            0
        );

        \add_filter(
            'WPCMSRoutingState:nature',
            [$this, 'getNature'],
            10,
            2
        );

        \add_action(
            '\PoP\ComponentModel\Engine_Vars:addVars',
            array($this, 'addURLParamVars'),
            20,
            1
        );
    }

    public function getNature($nature, $query)
    {
        if ($query->is_singular($this->getPostType())) {
            return RouteNatures::HOME;
        }

        return $nature;
    }

    public function addURLParamVars($vars_in_array)
    {
        if (\is_singular($this->getPostType())) {
            $vars = &$vars_in_array[0];
            global $post;
            $blocks = \parse_blocks($post->post_content);
            $graphiqlBlock = $blocks[0];
            $graphQLQuery = $graphiqlBlock['attrs']['query'];
            $variables = $graphiqlBlock['attrs']['variables'];
            if ($variables) {
                $vars['variables'] = $variables;
            }
            $graphQLQueryConvertor = GraphQLQueryConvertorFacade::getInstance();
            $fieldQuery = $graphQLQueryConvertor->convertFromGraphQLToFieldQuery($graphQLQuery, $variables);
            // Convert the query to an array
            $vars['query'] = FieldQueryConvertorUtils::getQueryAsArray($fieldQuery);
        }
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @return void
     */
    public function maybeSetAPIRequest(): void
    {
        if (is_singular($this->getPostType())) {

            EndpointHandler::setDoingGraphQL();
//             // $_REQUEST[QueryInputs::QUERY] = GraphQLDataStructureFormatter::getName();
            // $_REQUEST[QueryInputs::QUERY] = '!userPostsComments';
            $_REQUEST[QueryInputs::QUERY] = '';

//         // GraphQL queries
//         $userPropsGraphQLPersistedQuery = <<<EOT
//         query {
//             users {
//                 ...userProps
//                 posts {
//                     id
//                     title
//                     url
//                     comments {
//                         id
//                         date
//                         content
//                     }
//                 }
//             }
//         }

//         fragment userProps on User {
//             id
//             name
//             url
//         }
// EOT;
//         // Inject the values into the service
//         GraphQLPersistedQueryUtils::addPersistedQuery(
//             'userPostsComments',
//             $userPropsGraphQLPersistedQuery,
//             \__('User properties, posts and comments', 'examples-for-pop')
//         );
        }
    }
}
