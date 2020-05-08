<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use PoP\AccessControl\Schema\SchemaModes;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AbstractBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\SchemaConfigurationBlockCategory;

/**
 * Schema Config Options block
 */
class SchemaConfigOptionsBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_USE_NAMESPACING = 'useNamespacing';
    public const ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE = 'defaultSchemaMode';

    // public const ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT = 'default';
    public const ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED = 'enabled';
    public const ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED = 'disabled';

    protected function getBlockName(): string
    {
        return 'schema-config-options';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return new SchemaConfigurationBlockCategory();
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong> %s</p>';
        $schemaModeLabels = [
            SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
            SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
        ];
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Default Schema Mode for the Access Control Lists:', 'graphql-api'),
            $schemaModeLabels[$attributes[self::ATTRIBUTE_NAME_DEFAULT_SCHEMA_MODE]] ?? ComponentConfiguration::getSettingsValueLabel()
        );

        $useNamespacingLabels = [
            self::ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED => \__('yes', 'graphql-api'),
            self::ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED => \__('no', 'graphql-api'),
        ];
        $blockContent .= sprintf(
            $blockContentPlaceholder,
            \__('Use namespacing?', 'graphql-api'),
            $useNamespacingLabels[$attributes[self::ATTRIBUTE_NAME_USE_NAMESPACING]] ?? ComponentConfiguration::getSettingsValueLabel()
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
            $blockContent
        );
    }

    /**
     * Block align class
     *
     * @return boolean
     */
    public function getAlignClass(): string
    {
        return 'aligncenter';
    }
}
