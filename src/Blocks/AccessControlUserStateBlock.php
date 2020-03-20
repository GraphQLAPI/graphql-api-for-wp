<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use PoP\UserStateAccessControl\ConfigurationEntries\UserStates;

/**
 * Access Control Disable Access block
 */
class AccessControlUserStateBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-state';
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    public function renderBlock(array $attributes, string $content): string
	{
        $label = $attributes['value'] == UserStates::IN ?
            __('Logged-in users', 'graphql-api') :
            __('Not logged-in users', 'graphql-api');
		return sprintf(
            '<ul class="%s"><li>%s</li></ul>',
            $this->getBlockClassName(),
            $label
        );
	}
}
