<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractBlock;

/**
 * Base Control block
 */
abstract class AbstractControlBlock extends AbstractBlock
{
    use WithTypeFieldControlBlockTrait;

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
	{
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName().'-front';
        $typeFields = $attributes['typeFields'] ?? [];
        $directives = $attributes['directives'] ?? [];
        $fieldTypeContent = $directiveContent = '---';
        if ($typeFields) {
            $fieldTypeContent = '';
            foreach ($this->getTypeFieldsForPrint($typeFields, true) as $typeName => $fields) {
                $fieldTypeContent .= sprintf(
                    '<strong>%s</strong><ul><li>%s</li></ul>',
                    $typeName,
                    implode(
                        '</li><li>',
                        $fields
                    )
                );
            }
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
            __('Fields, by type:', 'graphql-api'),
            $fieldTypeContent,
            __('(Non-system) Directives:', 'graphql-api'),
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
            $this->getBlockDataTitle(),
            $blockDataContent,
            $className.'__content',
            $className.'__title',
            $this->getBlockContentTitle(),
            $this->getBlockContent($attributes, $content)
        );
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Select fields and directives:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Configuration:', 'graphql-api');
    }
    abstract protected function getBlockContent(array $attributes, string $content): string;
}
