<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control User Capabilities block
 */
class AccessControlUserCapabilitiesBlock extends AbstractAccessControlNestedBlock
{
    protected function getBlockName(): string
    {
        return 'access-control-user-capabilities';
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock($attributes, $content): string
	{
        $values = $attributes['value'] ?? [];
        return sprintf(
            '<div class="%s"><p><strong>%s</strong></p><ul><li>%s</li></ul></div>',
            $this->getBlockClassName(),
            __('Users with any of these capabilities:', 'graphql-api'),
            implode('</li><li>', $values)
        );
	}
}
