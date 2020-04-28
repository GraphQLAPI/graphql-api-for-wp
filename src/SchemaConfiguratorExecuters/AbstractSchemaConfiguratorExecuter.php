<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfiguratorExecuters;

use Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators\SchemaConfiguratorInterface;

abstract class AbstractSchemaConfiguratorExecuter
{
    /**
     * Initialize the configuration if vising the corresponding CPT
     *
     * @return void
     */
    public function init(): void
    {
        if (\is_singular($this->getPostType())) {
            global $post;
            $schemaConfigurator = $this->getSchemaConfigurator();
            $schemaConfigurator->executeSchemaConfiguration($post->ID);
        }
    }

    abstract protected function getPostType(): string;

    /**
     * Function to override, to initialize the configuration of services before the execution of the GraphQL query
     *
     * @return void
     */
    abstract protected function getSchemaConfigurator(): SchemaConfiguratorInterface;
}
