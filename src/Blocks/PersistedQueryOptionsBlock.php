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
    public const ATTRIBUTE_NAME_INHERIT_QUERY = 'inheritQuery';
    public const ATTRIBUTE_NAME_INHERIT_VARIABLES = 'inheritVariables';

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

        $labels = $this->getBooleanLabels();
        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Accept variables as URL params:', 'graphql-api'),
            $labels[$attributes[self::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? true]
        );
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Inherit query from ancestor(s):', 'graphql-api'),
            $labels[$attributes[self::ATTRIBUTE_NAME_INHERIT_QUERY] ?? false]
        );
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Inherit variables from ancestor(s):', 'graphql-api'),
            $labels[$attributes[self::ATTRIBUTE_NAME_INHERIT_VARIABLES] ?? false]
        );

        return $blockContent;
    }
}
