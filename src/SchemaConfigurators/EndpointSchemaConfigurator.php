<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\SchemaConfigurators;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractQueryExecutionOptionsBlock;

class EndpointSchemaConfigurator extends AbstractQueryExecutionSchemaConfigurator
{
    protected function getQueryExecutionOptionsBlock(): AbstractQueryExecutionOptionsBlock
    {
        return PluginState::getEndpointOptionsBlock();
    }
}
