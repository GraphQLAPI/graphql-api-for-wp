<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control List block
 */
abstract class AbstractAccessControlBlock extends AbstractGraphQLByPoPBlock
{
    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock($attributes, $content): string
	{
		$content = sprintf(
            '<div class="%s">',
            $this->getBlockClassName()
        );
		$content .= sprintf(
			'<p>%s</p>',
			__('Lorem ipsum...', 'graphql-api')
        );
		$content .= '</div>';
		return $content;
	}
}
