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
    public const ATTRIBUTE_NAME_SCHEMA_CONFIGURATION = 'schemaConfiguration';
    /**
     * These consts must be integer!
     */
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT = 0;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE = -1;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT = -2;

    use GraphQLByPoPBlockTrait;

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
        $content = sprintf(
            '<div class="%s">',
            $this->getBlockClassName() . ' ' . $this->getAlignClass()
        );
        $content .= 'saraza';
        $content .= '</div>';
        return $content;
    }
}
