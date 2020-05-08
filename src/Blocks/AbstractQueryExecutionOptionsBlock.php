<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Query Execution (endpoint and persisted query) Options block
 */
abstract class AbstractQueryExecutionOptionsBlock extends AbstractOptionsBlock
{
    public const ATTRIBUTE_NAME_IS_ENABLED = 'isEnabled';

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';
        $blockContentPlaceholder = <<<EOT
        <div class="%s">
            <h3 class="%s">%s</h3>
            %s
        </div>
EOT;
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClass(),
            $className . '__title',
            \__('Options', 'graphql-api'),
            $this->getBlockContent($attributes, $content)
        );
    }

    protected function getBlockContent(array $attributes, string $content): string
    {
        $labels = $this->getBooleanLabels();
        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        return sprintf(
            $blockContentPlaceholder,
            \__('Enabled:', 'graphql-api'),
            $labels[$attributes[self::ATTRIBUTE_NAME_IS_ENABLED] ?? true]
        );
    }
}
