<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfiguratorExecuters;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLPersistedQueryPostType;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\SchemaConfiguratorInterface;
use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\PersistedQuerySchemaConfigurator;

class PersistedQuerySchemaConfiguratorExecuter extends AbstractSchemaConfiguratorExecuter
{
    protected function getPostType(): string
    {
        return GraphQLPersistedQueryPostType::POST_TYPE;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return new PersistedQuerySchemaConfigurator();
    }
}
