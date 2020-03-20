<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control User Capabilities block
 */
abstract class AbstractItemListControlBlock extends AbstractBlock
{
    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
	{
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            %s
        </div>
EOF;
        $values = $attributes['value'];
        return sprintf(
            $blockContentPlaceholder,
            $this->getBlockClassName(),
            $values ?
                sprintf(
                    '<p><strong>%s</strong></p><ul><li><code>%s</code></li></ul>',
                    $this->getHeader(),
                    implode('</code></li><li><code>', $values)
                ) :
                sprintf(
                    '<em>%s</em>',
                    \__('(not set)', 'graphql-api')
                )
        );
    }

    abstract protected function getHeader(): string;
}
