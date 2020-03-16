<?php
namespace Leoloso\GraphQLByPoPWPPlugin\General;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;

class GraphiQLBlockHelpers {

    /**
     * Extract the GraphiQL block attributes from the post
     *
     * @param [type] $post
     * @return null|array an array of 2 items: [$query, $variables], or null if the post contains 0 or more than 1 GraphiQL blocks
     */
    public static function getSingleGraphiQLBlockAttributesFromPost($post): ?array {
		$blocks = \parse_blocks($post->post_content);
        // There must be only one block of type GraphiQL. Fetch it
        $graphiQLBlock = PluginState::getGraphiQLBlock();
        $graphiQLBlocks = array_filter(
            $blocks,
            function($block) use($graphiQLBlock) {
                return $block['blockName'] == $graphiQLBlock->getBlockFullName();
            }
        );
        // If there is either 0 or more than 1, return nothing
        if (count($graphiQLBlocks) != 1) {
            return null;
        }
        $graphiQLBlock = $graphiQLBlocks[0];
        return [
            $graphiQLBlock['attrs']['query'],
            $graphiQLBlock['attrs']['variables']
        ];
	}
}
