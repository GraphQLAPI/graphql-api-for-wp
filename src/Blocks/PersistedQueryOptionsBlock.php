<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;

/**
 * Persisted Query Options block
 */
class PersistedQueryOptionsBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'persisted-query-options';
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';
        $blockContentPlaceholder = <<<EOT
            <p><strong>%s</strong> %s</p>
EOT;
        $blockSchemaModeContent = sprintf(
            $blockContentPlaceholder,
            \__('Enabled:', 'graphql-api'),
            $attributes['isEnabled'] ? \__('yes', 'graphql-api') : \__('no', 'graphql-api')
        );
        $blockSchemaModeContent .= sprintf(
            $blockContentPlaceholder,
            \__('Accept variables as URL params:', 'graphql-api'),
            $attributes['acceptVariablesAsURLParams'] ? \__('yes', 'graphql-api') : \__('no', 'graphql-api')
        );

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
            $blockSchemaModeContent
        );
    }
}
