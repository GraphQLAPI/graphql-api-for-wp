<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

class BlockRenderingHelpers
{
    /**
     * Get a standardized title for a Custom Post
     *
     * @param Object $customPostObject
     * @return string The custom post's standardized title
     */
    public static function getCustomPostTitle($customPostObject): string
    {
        $title = $customPostObject->post_title ?
            $customPostObject->post_title :
            \__('(No title)', 'graphql-api');

        // If the post is either draft/pending (or maybe trash?), add that info in the title
        if ($customPostObject->post_status != 'publish') {
            $title = sprintf(
                \__('(%s) %s', 'graphql-api'),
                ucwords($customPostObject->post_status),
                $title
            );
        }
        return $title;
    }
}
