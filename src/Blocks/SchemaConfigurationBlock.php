<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AbstractBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\QueryExecutionBlockCategory;

/**
 * SchemaConfiguration block
 */
class SchemaConfigurationBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_SCHEMA_CONFIGURATION = 'schemaConfiguration';
    /**
     * These consts must be integer!
     */
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT = 0;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE = -1;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT = -2;

    protected function getBlockName(): string
    {
        return 'schema-configuration';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return new QueryExecutionBlockCategory();
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
    {
        $blockContent = sprintf(
            '<div class="%s">',
            $this->getBlockClassName() . ' ' . $this->getAlignClass()
        );
        $blockContent .= $content;
        $blockContent .= '</div>';
        return $blockContent;
    }
}
