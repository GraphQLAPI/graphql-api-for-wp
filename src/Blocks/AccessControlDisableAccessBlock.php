<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control Disable Access block
 */
class AccessControlDisableAccessBlock extends AbstractAccessControlNestedBlock
{
    protected function getBlockName(): string
    {
        return 'access-control-disable-access';
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock($attributes, $content): string
	{
		$blockContent = sprintf(
            '<div class="%s">',
            $this->getBlockClassName()
        );
		$blockContent .= sprintf(
			'<p>%s</p><p>%s</p>',
            __('No access', 'graphql-api'),
            json_encode($attributes)
        );
		$blockContent .= '</div>';
		return $blockContent;
	}
}
