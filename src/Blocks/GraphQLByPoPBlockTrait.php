<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Scripts\GraphQLByPoPScriptTrait;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPBlockTrait
{
    use GraphQLByPoPScriptTrait;

    protected function getBlockNamespace(): string
    {
        return 'graphql-api';
    }
}
