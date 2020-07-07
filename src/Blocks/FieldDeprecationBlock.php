<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\FieldDeprecationBlockCategory;

/**
 * Field Deprecation block
 */
class FieldDeprecationBlock extends AbstractControlBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_DEPRECATION_REASON = 'deprecationReason';

    protected function getBlockName(): string
    {
        return 'field-deprecation';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(FieldDeprecationBlockCategory::class);
    }

    protected function disableDirectives(): bool
    {
        return true;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function registerEditorCSS(): bool
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
