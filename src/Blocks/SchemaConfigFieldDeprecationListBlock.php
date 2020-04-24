<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\PostTypes\GraphQLFieldDeprecationListPostType;

/**
 * Cache Control block
 */
class SchemaConfigFieldDeprecationListBlock extends AbstractSchemaConfigPostListBlock
{
    protected function getBlockName(): string
    {
        return 'schema-config-field-deprecation-lists';
    }

    protected function getAttributeName(): string
    {
        return 'fieldDeprecationLists';
    }

    protected function getPostType(): string
    {
        return GraphQLFieldDeprecationListPostType::POST_TYPE;
    }

    protected function getHeader(): string
    {
        return \__('Field Deprecation Lists:');
    }
}
