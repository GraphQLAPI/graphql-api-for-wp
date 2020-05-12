<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\EditorScripts\GraphQLByPoPEditorScriptTrait;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPBlockTrait
{
    use GraphQLByPoPEditorScriptTrait;

    protected function getBlockNamespace(): string
    {
        return 'graphql-api';
    }
}
