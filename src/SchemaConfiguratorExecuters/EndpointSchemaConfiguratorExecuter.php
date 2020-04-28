<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfiguratorExecuters;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLEndpointPostType;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\EndpointSchemaConfigurator;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\SchemaConfiguratorInterface;

class EndpointSchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    protected function getPostType(): string
    {
        return GraphQLEndpointPostType::POST_TYPE;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return new EndpointSchemaConfigurator();
    }
}
