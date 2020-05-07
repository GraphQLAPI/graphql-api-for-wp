<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractBlock;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AbstractBlockCategory;
use Leoloso\GraphQLByPoPWPPlugin\BlockCategories\AccessControlBlockCategory;

/**
 * Access Control rule block
 */
abstract class AbstractAccessControlRuleBlock extends AbstractBlock
{
    public const ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP = 'accessControlGroup';
    public const ATTRIBUTE_NAME_VALUE = 'value';

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        return new AccessControlBlockCategory();
    }
}
