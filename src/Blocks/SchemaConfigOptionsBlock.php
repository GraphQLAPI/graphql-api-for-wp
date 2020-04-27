<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use PoP\AccessControl\Schema\SchemaModes;

/**
 * Schema Config Options block
 */
class SchemaConfigOptionsBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-options';
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
        $schemaModeLabels = [
            SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
            SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
        ];
        $blockSchemaModeContent = sprintf(
            $blockContentPlaceholder,
            \__('Default Schema Mode:', 'graphql-api'),
            $schemaModeLabels[$attributes['defaultSchemaMode']]
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
            \__('Options', 'graphql-api'),
            $blockSchemaModeContent
        );
    }
}
