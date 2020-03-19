<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPBlockTrait
{
    protected function getBlockNamespace(): string
    {
        return 'graphql-api';
    }

    protected function getPluginDir(): string
    {
        return \GRAPHQL_BY_POP_PLUGIN_DIR;
    }

    protected function getPluginURL(): string
    {
        return \GRAPHQL_BY_POP_PLUGIN_URL;
    }
}
