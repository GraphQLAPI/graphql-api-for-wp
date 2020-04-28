<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\General;

use Leoloso\GraphQLByPoPWPPlugin\Blocks\AbstractBlock;

class BlockHelpers
{
    /**
     * After parsing a post, cache its blocks
     *
     * @var array
     */
    protected static $blockCache = [];

    /**
     * Extract the blocks from the post
     *
     * @param mixed $configurationPostID
     * @return array
     */
    public static function getBlocksFromCustomPost(
        $configurationPostOrID
    ): array {
        if (\is_object($configurationPostOrID)) {
            $configurationPost = $configurationPostOrID;
            $configurationPostID = $configurationPost->ID;
        } else {
            $configurationPostID = $configurationPostOrID;
            // Only fetch the object if blocks not yet cached
            if (!isset(self::$blockCache[$configurationPostID])) {
                $configurationPost = \get_post($configurationPostID);
            } else {
                $configurationPost = self::$blockCache[$configurationPostID];
            }
        }
        // If there's either no post or ID, then that object doesn't exist (or maybe it's draft)
        if (!$configurationPost || !$configurationPostID) {
            return [];
        }

        // Get the blocks from the inner cache, if available
        if (isset(self::$blockCache[$configurationPostID])) {
            $blocks = self::$blockCache[$configurationPostID];
        } else {
            $blocks = \parse_blocks($configurationPost->post_content);
            self::$blockCache[$configurationPostID] = $blocks;
        }

        return $blocks;
    }

    /**
     * Read the configuration post, and extract the configuration, contained through the specified block
     *
     * @param mixed $configurationPostID
     * @param AbstractBlock $block
     * @return array
     */
    public static function getBlocksOfTypeFromCustomPost(
        $configurationPostOrID,
        AbstractBlock $block
    ): array {
        $blocks = self::getBlocksFromCustomPost($configurationPostOrID);

        // Obtain the blocks for the provided block type
        $blockFullName = $block->getBlockFullName();
        return array_values(array_filter(
            $blocks,
            function ($block) use ($blockFullName) {
                return $block['blockName'] == $blockFullName;
            }
        ));
    }

    /**
     * Read the single block of a certain type, contained in the post.
     * If there are more than 1, or none, return null
     *
     * @param mixed $configurationPostID
     * @param AbstractBlock $block
     * @return array
     */
    public static function getSingleBlockOfTypeFromCustomPost(
        $configurationPostOrID,
        AbstractBlock $block
    ): ?array {
        $blocks = self::getBlocksOfTypeFromCustomPost($configurationPostOrID, $block);
        if (count($blocks) != 1) {
            return null;
        }
        return $blocks[0];
    }
}
