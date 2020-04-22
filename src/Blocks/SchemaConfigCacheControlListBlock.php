<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;

/**
 * Cache Control block
 */
class SchemaConfigCacheControlListBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-cache-control-lists';
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
    {
        return 'saraza';
    }
}
