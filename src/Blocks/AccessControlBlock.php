<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;

/**
 * Access Control block
 */
class AccessControlBlock extends AbstractControlBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control';
    }

    protected function registerEditorCSS(): bool
    {
        return true;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Define access for:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Who can access:', 'graphql-api');
    }
    /**
     * Return the nested blocks' content
     *
     * @param array $attributes
     * @param string $content
     * @return string
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        if ($content) {
            return $content;
        }
        return sprintf(
            '<em>%s</em>',
            \__('(not set)', 'graphql-api')
        );
    }
}
