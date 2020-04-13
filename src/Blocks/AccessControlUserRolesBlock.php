<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control User Roles block
 */
class AccessControlUserRolesBlock extends AbstractItemListControlBlock
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
