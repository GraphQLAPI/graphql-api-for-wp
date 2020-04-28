<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;

/**
 * Persisted Query Options block
 */
class PersistedQueryOptionsBlock extends AbstractQueryExecutionOptionsBlock
{
    public const ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS = 'acceptVariablesAsURLParams';

    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'persisted-query-options';
    }

    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);

        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Accept variables as URL params:', 'graphql-api'),
            $attributes[self::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ? \__('yes', 'graphql-api') : \__('no', 'graphql-api')
        );

        return $blockContent;
    }
}
