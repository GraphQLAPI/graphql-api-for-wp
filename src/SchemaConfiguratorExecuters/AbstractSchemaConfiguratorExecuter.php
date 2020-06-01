<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters;

use PoP\ComponentModel\State\ApplicationState;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\SchemaConfiguratorInterface;

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
            // Watch out! If accessing $vars it triggers setting ComponentConfiguration vars,
            // but we have not set the hooks yet!
            // For instance for `namespaceTypesAndInterfaces()`,
            // to be set in `executeSchemaConfigurationOptionsNamespacing()`
            // Hence, code below was commented, and access the $post from the global variable
            // $vars = ApplicationState::getVars();
            // $postID = $vars['routing-state']['queried-object-id'];
            global $post;
            $postID = $post->ID;
            $schemaConfigurator = $this->getSchemaConfigurator();
            $schemaConfigurator->executeSchemaConfiguration($postID);
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
