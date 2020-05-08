<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AbstractBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\PersistedQueryBlockCategory;

/**
 * Persisted Query Options block
 */
class PersistedQueryOptionsBlock extends AbstractQueryExecutionOptionsBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS = 'acceptVariablesAsURLParams';

    protected function getBlockName(): string
    {
        return 'persisted-query-options';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return new PersistedQueryBlockCategory();
    }

    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);

        $labels = [
            true =>  \__('yes', 'graphql-api'),
            false =>  __('no', 'graphql-api'),
        ];
        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Accept variables as URL params:', 'graphql-api'),
            $labels[$attributes[self::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? true]
        );

        return $blockContent;
    }
}
