<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control User Roles block
 */
class AccessControlUserRolesBlock extends AbstractSelectableControlBlock
{
    use GraphQLByPoPBlockTrait;

    protected function getBlockName(): string
    {
        return 'access-control-user-roles';
    }

    protected function getHeader(): string
    {
        return __('Users with any of these roles:', 'graphql-api');
    }
}
