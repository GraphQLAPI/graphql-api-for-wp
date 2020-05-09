<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\EditorScripts;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPEditorScriptTrait
{
    protected function getPluginDir(): string
    {
        return \GRAPHQL_BY_POP_PLUGIN_DIR;
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim(\GRAPHQL_BY_POP_PLUGIN_URL, '/');
    }
}
