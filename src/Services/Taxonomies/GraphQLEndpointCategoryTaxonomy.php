<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class GraphQLEndpointCategoryTaxonomy extends AbstractCategory
{
    use StandaloneServiceTrait;

    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;
    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }
    final public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        return $this->graphQLPersistedQueryEndpointCustomPostType ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
    }

    public function getTaxonomy(): string
    {
        return 'graphql-endpoint-category';
    }

    public function getTaxonomyName(bool $titleCase = true): string
    {
        return $titleCase ? \__('GraphQL Endpoint Category', 'graphql-api') : \__('GraphQL endpoint category', 'graphql-api');
    }

    /**
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    public function getTaxonomyPluralNames(bool $titleCase = true): string
    {
        return $titleCase ? \__('Endpoint Categories', 'graphql-api') : \__('endpoint categories', 'graphql-api');
    }

    /**
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType(),
            $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType(),
        ];
    }
}
