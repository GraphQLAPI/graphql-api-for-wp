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
    public static function getPostSingleGraphiQLBlockAttributes($post): ?array {
		$blocks = \parse_blocks($post->post_content);
        // There must be only one block of type GraphiQL. Fetch it
        $graphiQLBlock = PluginState::getGraphiQLBlock();
        $graphiqlBlocks = array_filter(
            $blocks,
            function($block) use($graphiQLBlock) {
                return $block['blockName'] == $graphiQLBlock->getBlockFullName();
            }
        );
        // If there is either 0 or more than 1, return nothing
        if (count($graphiqlBlocks) != 1) {
            return null;
        }
        $graphiqlBlock = $graphiqlBlocks[0];
        return [
            $graphiqlBlock['attrs']['query'],
            $graphiqlBlock['attrs']['variables']
        ];
	}
}
