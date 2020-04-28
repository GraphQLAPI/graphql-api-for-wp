<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Blocks;

/**
 * Access Control rule block
 */
abstract class AbstractAccessControlRuleBlock extends AbstractBlock
{
    public const ATTRIBUTE_NAME_ACCESS_CONTROL_GROUP = 'accessControlGroup';
    public const ATTRIBUTE_NAME_VALUE = 'value';
}
