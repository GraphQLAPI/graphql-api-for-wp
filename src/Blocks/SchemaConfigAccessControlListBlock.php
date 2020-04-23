<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLAccessControlListPostType;

/**
 * Cache Control block
 */
class SchemaConfigAccessControlListBlock extends AbstractSchemaConfigPostListBlock
{
    protected function getBlockName(): string
    {
        return 'schema-config-access-control-lists';
    }

    protected function getAttributeName(): string
    {
        return 'accessControlLists';
    }

    protected function getPostType(): string
    {
        return GraphQLAccessControlListPostType::POST_TYPE;
    }

    protected function getHeader(): string
    {
        return \__('Access Control Lists:');
    }
}
