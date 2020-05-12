<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\General;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\GraphiQLBlock;
use Leoloso\GraphQLByPoPWPPlugin\Blocks\PersistedQueryOptionsBlock;

class BlockContentHelpers
{
    /**
     * Extract the GraphiQL block attributes from the post
     *
     * @param [type] $post
     * @return null|array an array of 2 items: [$query, $variables], or null if the post contains 0 or more than 1 block
     */
    public static function getSingleGraphiQLBlockAttributesFromPost($post): ?array
    {
        // There must be only one block of type GraphiQL. Fetch it
        $graphiQLBlock = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $post,
            PluginState::getGraphiQLBlock()
        );
        // If there is either 0 or more than 1, return nothing
        if (is_null($graphiQLBlock)) {
            return null;
        }
        return [
            $graphiQLBlock['attrs'][GraphiQLBlock::ATTRIBUTE_NAME_QUERY],
            $graphiQLBlock['attrs'][GraphiQLBlock::ATTRIBUTE_NAME_VARIABLES]
        ];
    }

    /**
     * Extract the Persisted Query Options block attributes from the post
     *
     * @param [type] $post
     * @return null|array an array of 1 item: [$inheritQuery], or null if the post contains 0 or more than 1 block
     */
    public static function getSinglePersistedQueryOptionsBlockAttributesFromPost($post): ?array
    {
        // There must be only one block of type PersistedQueryOptionsBlock. Fetch it
        $block = BlockHelpers::getSingleBlockOfTypeFromCustomPost(
            $post,
            PluginState::getPersistedQueryOptionsBlock()
        );
        // If there is either 0 or more than 1, return nothing
        if (is_null($block)) {
            return null;
        }
        return [
            $block['attrs'][PersistedQueryOptionsBlock::ATTRIBUTE_NAME_INHERIT_QUERY],
        ];
    }
}
