<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\WithTypeFieldControlBlockTrait;

/**
 * Access Control block
 */
class AccessControlBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait, WithTypeFieldControlBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control';
    }

    protected function registerEditorCSS(): bool
    {
        return true;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock($attributes, $content): string
	{
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName().'-front';
        $typeFields = $attributes['typeFields'] ?? [];
        $directives = $attributes['directives'] ?? [];
        $fieldTypeContent = $directiveContent = '---';
        if ($typeFields) {
            $fieldTypeContent = sprintf(
                '<ul><li>%s</li></ul>',
                implode(
                    '</li><li>',
                    $this->getTypeFieldsForPrint($typeFields)
                )
            );
        }
        if ($directives) {
            $directiveContent = sprintf(
                '<ul><li>%s</li></ul>',
                implode('</li><li>', $directives)
            );
        }
        $blockDataPlaceholder = <<<EOT
            <p><strong>%s</strong></p>
            %s
            <p><strong>%s</strong></p>
            %s
EOT;
        $blockDataContent = sprintf(
            $blockDataPlaceholder,
            __('Fields, by type', 'graphql-api'),
            $fieldTypeContent,
            __('(Non-system) Directives', 'graphql-api'),
            $directiveContent
        );
        $blockContentPlaceholder = <<<EOT
        <div class="%s">
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
        </div>
EOT;
        return sprintf(
            $blockContentPlaceholder,
            $className.' '.$this->getAlignClass(),
            $className.'__data',
            $className.'__title',
            __('Define access for:', 'graphql-api'),
            $blockDataContent,
            $className.'__who',
            $className.'__title',
            __('Who can access:', 'graphql-api'),
            $content
        );
	}
}
