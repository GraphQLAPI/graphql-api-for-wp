<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks\Overrides;

use GraphQLAPI\GraphQLAPI\Blocks\GraphiQLBlock;

/**
 * GraphiQL with Explorer block
 */
class GraphiQLWithExplorerBlock extends GraphiQLBlock
{
 /**
     * Override the location of the script
     *
     * @return string
     */
    protected function getBlockDirURL(): string
    {
        return $this->getPluginURL() . '/blocks/graphiql-with-explorer/';
    }
}
