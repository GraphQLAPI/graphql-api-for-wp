<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control block
 */
class AccessControlBlock extends AbstractAccessControlBlock
{
    /**
     * When saving access control for a field, the format is "typeNamespacedName.fieldName"
     */
    public const TYPE_FIELD_SEPARATOR = '.';

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
        $className = $this->getBlockClassName().'-front';
        $blockContentPlaceholder = <<<EOT
        <div class="%s alignwide">
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
            $className,
            $className.'__data',
            $className.'__title',
            __('Define access for:', 'graphql-api'),
            json_encode($attributes),
            $className.'__who',
            $className.'__title',
            __('Who can access:', 'graphql-api'),
            $content
        );
	}
}
