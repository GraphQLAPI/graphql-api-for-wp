<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use PoP\AccessControl\Schema\SchemaModes;
use PoP\AccessControl\ComponentConfiguration;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractControlBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AbstractBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AccessControlBlockCategory;

/**
 * Access Control block
 */
class AccessControlBlock extends AbstractControlBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_SCHEMA_MODE = 'schemaMode';

    protected function getBlockName(): string
    {
        return 'access-control';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return new AccessControlBlockCategory();
    }

    protected function registerEditorCSS(): bool
    {
        return true;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Define access for:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        if (ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            return \__('Access Control Rules:', 'graphql-api');
        }
        return \__('Who can access:', 'graphql-api');
    }

    /**
     * Pass localized data to the block
     *
     * @return array
     */
    protected function getLocalizedData(): array
    {
        return [
            'enableIndividualControlForSchemaMode' => ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode(),
        ];
    }

    /**
     * Return the nested blocks' content
     *
     * @param array $attributes
     * @param string $content
     * @return string
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        $maybeSchemaModeContent = '';
        if (ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            $blockContentPlaceholder = <<<EOT
                <p><strong>%s</strong> %s</p>
                <h4 class="%s">%s</h4>
EOT;
            $className = $this->getBlockClassName() . '-front';
            $schemaModeLabels = [
                SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
                SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
            ];
            $maybeSchemaModeContent = sprintf(
                $blockContentPlaceholder,
                \__('Schema mode:', 'graphql-api'),
                $attributes[self::ATTRIBUTE_NAME_SCHEMA_MODE] ?
                    $schemaModeLabels[$attributes[self::ATTRIBUTE_NAME_SCHEMA_MODE]]
                    : \__('Default', 'graphql-api'),
                $className . '__title',
                \__('Who can access:', 'graphql-api')
            );
        }
        if ($content) {
            return $maybeSchemaModeContent . $content;
        }
        return $maybeSchemaModeContent . sprintf(
            '<em>%s</em>',
            \__('(not set)', 'graphql-api')
        );
    }
}
