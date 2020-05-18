<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlRuleBlocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphQLByPoPBlockTrait;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AccessControlRuleBlocks\AbstractItemListAccessControlRuleBlock;

/**
 * Access Control User Capabilities block
 */
class AccessControlUserCapabilitiesBlock extends AbstractItemListAccessControlRuleBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-capabilities';
    }

    protected function getHeader(): string
    {
        return __('Users with any of these capabilities', 'graphql-api');
    }
}
