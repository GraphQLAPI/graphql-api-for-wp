<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

/**
 * Query Execution (endpoint and persisted query) Options block
 */
abstract class AbstractOptionsBlock extends AbstractBlock
{
    protected function getBooleanLabels(): array
    {
        return [
            true =>  \__('✅ Yes', 'graphql-api'),
            false =>  __('❌ No', 'graphql-api'),
        ];
    }
}
