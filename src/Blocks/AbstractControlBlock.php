<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractBlock;
use Leoloso\GraphQLByPoPWPPlugin\ComponentConfiguration;

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

    protected function disableFields(): bool
    {
        return false;
    }

    protected function disableDirectives(): bool
    {
        return false;
    }

    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';
        $fieldTypeContent = $directiveContent = '';
        if (!$this->disableFields()) {
            $fieldTypeContent = ComponentConfiguration::getEmptyLabel();
            $typeFields = $attributes['typeFields'] ?? [];
            if ($typeFields) {
                $typeFieldsForPrint = $this->getTypeFieldsForPrint($typeFields);
                /**
                 * If $groupFieldsUnderTypeForPrint is true, combine all types under their shared typeName
                 * If $groupFieldsUnderTypeForPrint is false, replace namespacedTypeName for typeName and "." for "/"
                 * */
                $groupFieldsUnderTypeForPrint = ComponentConfiguration::groupFieldsUnderTypeForPrint();
                if ($groupFieldsUnderTypeForPrint) {
                    $fieldTypeContent = '';
                    foreach ($typeFieldsForPrint as $typeName => $fields) {
                        $fieldTypeContent .= sprintf(
                            '<strong>%s</strong><ul><li><code>%s</code></li></ul>',
                            $typeName,
                            implode(
                                '</code></li><li><code>',
                                $fields
                            )
                        );
                    }
                } else {
                    $fieldTypeContent = sprintf(
                        '<ul><li>%s</li></ul>',
                        implode(
                            '</li><li>',
                            $typeFieldsForPrint
                        )
                    );
                }
            }
        }
        if (!$this->disableDirectives()) {
            $directiveContent = ComponentConfiguration::getEmptyLabel();
            $directives = $attributes['directives'] ?? [];
            if ($directives) {
                $directiveContent = sprintf(
                    '<ul><li><code>%s</code></li></ul>',
                    implode('</code></li><li><code>', $directives)
                );
            }
        }
        $blockDataContent = '';
        if (!$this->disableFields() && !$this->disableDirectives()) {
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
        } elseif (!$this->disableFields()) {
            $blockDataContent = $fieldTypeContent;
        } elseif (!$this->disableDirectives()) {
            $blockDataContent = $directiveContent;
        }

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
            $className . ' ' . $this->getAlignClass(),
            $className . '__data',
            $className . '__title',
            $this->getBlockDataTitle(),
            $blockDataContent,
            $className . '__content',
            $className . '__title',
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
