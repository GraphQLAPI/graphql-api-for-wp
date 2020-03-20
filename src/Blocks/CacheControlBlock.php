<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;

/**
 * Cache Control block
 */
class CacheControlBlock extends AbstractControlBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'cache-control';
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Define cache max-age for:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Cache max age (in seconds):', 'graphql-api');
    }
    protected function getBlockContent(array $attributes, string $content): string
    {
        return 'sarlanagaga';
    }
}
