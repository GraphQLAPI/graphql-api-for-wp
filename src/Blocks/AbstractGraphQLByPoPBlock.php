<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

abstract class AbstractGraphQLByPoPBlock extends AbstractBlock {

    protected function getBlockNamespace(): string
    {
        return 'graphql-by-pop';
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
