<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control List block
 */
abstract class AbstractAccessControlListBlock extends AbstractGraphQLByPoPBlock
{
    public const TYPE_FIELD_SEPARATOR = '.';

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
