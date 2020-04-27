<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use PoP\AccessControl\Schema\SchemaModes;

/**
 * Endpoint Options block
 */
class EndpointOptionsBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'endpoint-options';
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
