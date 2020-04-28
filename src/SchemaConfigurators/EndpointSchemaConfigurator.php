<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLEndpointPostType;

class EndpointSchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
    protected function getPostType(): string
    {
        return GraphQLEndpointPostType::POST_TYPE;
    }
}
