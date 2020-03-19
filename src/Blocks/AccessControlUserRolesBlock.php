<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control Disable Access block
 */
class AccessControlUserRolesBlock extends AbstractAccessControlNestedBlock
{
    protected function getBlockName(): string
    {
        return 'access-control-user-roles';
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
            __('Users with any of these roles:', 'graphql-api'),
            implode('</li><li>', $values)
        );
	}
}
