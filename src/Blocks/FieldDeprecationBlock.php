<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;

/**
 * Field Deprecation block
 */
class FieldDeprecationBlock extends AbstractControlBlock
{
    public const ATTRIBUTE_NAME_DEPRECATION_REASON = 'deprecationReason';

    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'field-deprecation';
    }

    protected function disableDirectives(): bool
    {
        return true;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Fields to deprecate:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Deprecation reason:', 'graphql-api');
    }
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            %s
        </div>
EOF;
        $deprecationReason = $attributes[self::ATTRIBUTE_NAME_DEPRECATION_REASON];
        if (!$deprecationReason) {
            $deprecationReason = sprintf(
                '<em>%s</em>',
                \__('(not set)', 'graphql-api')
            );
        }
        return sprintf(
            $blockContentPlaceholder,
            $this->getBlockClassName() . '__content',
            $deprecationReason
        );
    }
}
