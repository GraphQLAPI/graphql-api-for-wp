<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\PostTypes;

use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\PostTypes\AbstractGraphQLQueryExecutionPostType;
use Leoloso\GraphQLByPoPWPPlugin\Taxonomies\GraphQLQueryTaxonomy;
use PoP\GraphQLAPIRequest\Execution\QueryExecutionHelpers;

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
}
