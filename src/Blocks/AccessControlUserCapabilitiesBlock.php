<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control User Capabilities block
 */
class AccessControlUserCapabilitiesBlock extends AbstractSelectableControlBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-capabilities';
    }

    protected function getHeader(): string
    {
        return __('Users with any of these capabilities:', 'graphql-api');
    }
}
