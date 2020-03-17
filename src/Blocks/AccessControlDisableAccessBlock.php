<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control List block
 */
class AccessControlDisableAccessBlock extends AbstractAccessControlListBlock
{
    protected function getBlockName(): string
    {
        return 'access-control-disable-access';
    }
}
