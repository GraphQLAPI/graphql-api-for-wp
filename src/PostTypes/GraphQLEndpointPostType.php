<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use PoP\ComponentModel\Misc\RequestUtils;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\General\RequestParams;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use PoP\GraphQLAPIRequest\Execution\QueryExecutionHelpers;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\EndpointOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\Taxonomies\GraphQLQueryTaxonomy;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractGraphQLQueryExecutionPostType;

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
        return !in_array($_REQUEST[RequestParams::VIEW], ['graphiql', 'schema']);
    }

    /**
     * Set the hook to expose the GraphiQL/Voyager clients
     *
     * @return void
     */
    protected function doSomethingElse(): void
    {
        \add_action(
            'wp',
            [$this, 'maybePrintClient']
        );
    }
    /**
     * Expose the GraphiQL/Voyager clients
     *
     * @return void
     */
    public function maybePrintClient(): void
    {
        // Read from the configuration if to expose the GraphiQL/Voyager client
        $exposeClient = false;
        $optionsBlockDataItem = $this->getOptionsBlockDataItem();
        if ($_REQUEST[RequestParams::VIEW] == 'graphiql') {
            // `true` is the default option in Gutenberg, so it's not saved to the DB!
            $exposeClient = $optionsBlockDataItem['attrs'][EndpointOptionsBlock::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED] ?? true;
        } elseif ($_REQUEST[RequestParams::VIEW] == 'schema') {
            $exposeClient = $optionsBlockDataItem['attrs'][EndpointOptionsBlock::ATTRIBUTE_NAME_IS_VOYAGER_ENABLED] ?? true;
        }
        if (!$exposeClient) {
            return;
        }

        // Read from the static HTML files and replace their endpoints
        $dirPaths = [
            'graphiql' => '/vendor/leoloso/pop-graphiql',
            'schema' => '/vendor/leoloso/pop-graphql-voyager',
        ];
        if ($dirPath = $dirPaths[$_REQUEST[RequestParams::VIEW]]) {
            // Read the file, and return it already
            $file = \GRAPHQL_BY_POP_PLUGIN_DIR . $dirPath . '/index.html';
            $fileContents = \file_get_contents($file, true);
            // Modify the script path
            $jsFileNames = [
                'graphiql' => 'graphiql.js',
                'schema' => 'voyager.js',
            ];
            if ($jsFileName = $jsFileNames[$_REQUEST[RequestParams::VIEW]]) {
                $jsFileURL = \trim(\GRAPHQL_BY_POP_PLUGIN_URL, '/') . $dirPath . '/' . $jsFileName;
                $useNamespace = '';
                if (false) {
                    $useNamespace = '&use_namespace=1';
                }
                $endpointURL = \remove_query_arg(RequestParams::VIEW, RequestUtils::getCurrentUrl());
                $endpointURL .= $useNamespace;
                $fileContents = \str_replace($jsFileName . '?', $jsFileURL . '?endpoint=' . $endpointURL . '&', $fileContents);
            }
            // Print, and that's it!
            echo $fileContents;
            die;
        }
    }
}
