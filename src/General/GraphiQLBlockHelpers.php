<?php
namespace Leoloso\GraphQLByPoPWPPlugin\General;

use Leoloso\GraphQLByPoPWPPlugin\PluginState;

class GraphiQLBlockHelpers {

    /**
     * Extract the GraphiQL block attributes from the post
     *
     * @param [type] $post
     * @return array an array of 2 items: [$query, $variables]
     */
    public static function getSingleGraphiQLBlockAttributesFromPost($post): ?array {
		$blocks = \parse_blocks($post->post_content);
        // There must be only one block of type GraphiQL. Fetch it
        $graphiQLBlock = PluginState::getGraphiQLBlock();
        $graphiqlBlocks = array_filter(
            $blocks,
            function($block) use($graphiQLBlock) {
                return $block['blockName'] == $graphiQLBlock->getBlockFullName();
            }
        );
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
