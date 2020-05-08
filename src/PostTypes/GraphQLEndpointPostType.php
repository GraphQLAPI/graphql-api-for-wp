<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\General\RequestParams;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use PoP\GraphQLAPIRequest\Execution\QueryExecutionHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\EndpointOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Taxonomies\GraphQLQueryTaxonomy;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractGraphQLQueryExecutionPostType;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\API\Configuration\Request;

class GraphQLEndpointPostType extends AbstractGraphQLQueryExecutionPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-endpoint';

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
     * Access endpoints under /graphql, or wherever it is configured to
     *
     * @return string|null
     */
    protected function getSlugBase(): ?string
    {
        return ComponentConfiguration::getEndpointSlugBase();
    }

    /**
     * Custom post type name
     *
     * @return void
     */
    public function getPostTypeName(): string
    {
        return \__('GraphQL endpoint', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('GraphQL endpoints', 'graphql-api');
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
                'all_items' => \__('Endpoints', 'graphql-api'),
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

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergTemplate(): array
    {
        $schemaConfigurationBlock = PluginState::getSchemaConfigurationBlock();
        $endpointOptionsBlock = PluginState::getEndpointOptionsBlock();
        return [
            [$schemaConfigurationBlock->getBlockFullName()],
            [$endpointOptionsBlock->getBlockFullName()],
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
     * Provide the query to execute and its variables
     *
     * @return array
     */
    protected function getGraphQLQueryAndVariables(): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        return QueryExecutionHelpers::getRequestedGraphQLQueryAndVariables();
    }

    protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock
    {
        return PluginState::getEndpointOptionsBlock();
    }

    /**
     * Indicates if we executing the GraphQL query (`true`) or visualizing the query source (`false`)
     * It returns always `true`, unless passing ?view=source in the single post URL
     *
     * @return boolean
     */
    protected function isGraphQLQueryExecution(): bool
    {
        return !in_array(
            $_REQUEST[RequestParams::VIEW],
            [
                RequestParams::VIEW_GRAPHIQL,
                RequestParams::VIEW_SCHEMA,
                RequestParams::VIEW_SOURCE,
            ]
        );
    }

    /**
     * Set the hook to expose the GraphiQL/Voyager clients
     *
     * @return void
     */
    protected function doSomethingElse(): void
    {
        if ($_REQUEST[RequestParams::VIEW] == RequestParams::VIEW_SOURCE) {
            parent::doSomethingElse();
        } else {
            /**
             * Execute at the very last, because Component::boot is executed also on hook "wp",
             * and there is useNamespacing set
             */
            \add_action(
                'wp',
                [$this, 'maybePrintClient'],
                PHP_INT_MAX
            );
        }
    }
    /**
     * Expose the GraphiQL/Voyager clients
     *
     * @return void
     */
    public function maybePrintClient(): void
    {
        global $post;
        // Read from the configuration if to expose the GraphiQL/Voyager client
        switch ($_REQUEST[RequestParams::VIEW]) {
            case RequestParams::VIEW_GRAPHIQL:
                $exposeClient = $this->isGraphiQLEnabled($post);
                break;
            case RequestParams::VIEW_SCHEMA:
                $exposeClient = $this->isVoyagerEnabled($post);
                break;
            default:
                $exposeClient = false;
        }
        if (!$exposeClient) {
            return;
        }

        // Read from the static HTML files and replace their endpoints
        $dirPaths = [
            RequestParams::VIEW_GRAPHIQL => '/vendor/leoloso/pop-graphiql',
            RequestParams::VIEW_SCHEMA => '/vendor/leoloso/pop-graphql-voyager',
        ];
        if ($dirPath = $dirPaths[$_REQUEST[RequestParams::VIEW]]) {
            // Read the file, and return it already
            $file = \GRAPHQL_BY_POP_PLUGIN_DIR . $dirPath . '/index.html';
            $fileContents = \file_get_contents($file, true);
            // Modify the script path
            $jsFileNames = [
                RequestParams::VIEW_GRAPHIQL => 'graphiql.js',
                RequestParams::VIEW_SCHEMA => 'voyager.js',
            ];
            if ($jsFileName = $jsFileNames[$_REQUEST[RequestParams::VIEW]]) {
                $jsFileURL = \trim(\GRAPHQL_BY_POP_PLUGIN_URL, '/') . $dirPath . '/' . $jsFileName;
                $endpointURL = \remove_query_arg(RequestParams::VIEW, \fullUrl());
                if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
                    $endpointURL = \add_query_arg(Request::URLPARAM_USE_NAMESPACE, true, $endpointURL);
                }
                $fileContents = \str_replace(
                    $jsFileName . '?',
                    $jsFileURL . '?endpoint=' . urlencode($endpointURL) . '&',
                    $fileContents
                );
            }
            // Print, and that's it!
            echo $fileContents;
            die;
        }
    }
    
    /**
     * Read the options block and check the value of attribute "isGraphiQLEnabled"
     *
     * @return void
     */
    protected function isGraphiQLEnabled($postOrID): bool
    {
        // If the endpoint is disabled, then also disable this client
        if (!$this->isEnabled($postOrID)) {
            return false;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            EndpointOptionsBlock::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED,
            true
        );
    }
    
    /**
     * Read the options block and check the value of attribute "isVoyagerEnabled"
     *
     * @return void
     */
    protected function isVoyagerEnabled($postOrID): bool
    {
        // If the endpoint is disabled, then also disable this client
        if (!$this->isEnabled($postOrID)) {
            return false;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $this->isOptionsBlockValueOn(
            $postOrID,
            EndpointOptionsBlock::ATTRIBUTE_NAME_IS_VOYAGER_ENABLED,
            true
        );
    }

    /**
     * Get actions to add for this CPT
     *
     * @param Object $post
     * @return array
     */
    protected function getPostTypeTableActions($post): array
    {
        $actions = parent::getPostTypeTableActions($post);

        /**
         * If neither GraphiQL or Voyager are enabled, then already return
         */
        $isGraphiQLEnabled = $this->isGraphiQLEnabled($post);
        $isVoyagerEnabled = $this->isVoyagerEnabled($post);
        if (!$isGraphiQLEnabled && !$isVoyagerEnabled) {
            return $actions;
        }

        $title = \_draft_or_post_title();
        $permalink = \get_permalink($post->ID);
        /**
         * Attach the GraphiQL/Voyager clients
         */
        return array_merge(
            $actions,
            // If GraphiQL enabled, add the "GraphiQL" action
            $isGraphiQLEnabled ? [
                'graphiql' => sprintf(
                    '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                    \add_query_arg(
                        RequestParams::VIEW,
                        RequestParams::VIEW_GRAPHIQL,
                        $permalink
                    ),
                    /* translators: %s: Post title. */
                    \esc_attr(\sprintf(\__('GraphiQL &#8220;%s&#8221;'), $title)),
                    __('GraphiQL', 'graphql-api')
                ),
            ] : [],
            // If Voyager enabled, add the "Schema" action
            $isVoyagerEnabled ? [
                'schema' => sprintf(
                    '<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
                    \add_query_arg(
                        RequestParams::VIEW,
                        RequestParams::VIEW_SCHEMA,
                        $permalink
                    ),
                    /* translators: %s: Post title. */
                    \esc_attr(\sprintf(\__('Schema &#8220;%s&#8221;'), $title)),
                    __('Schema', 'graphql-api')
                )
            ] : []
        );
    }
}
