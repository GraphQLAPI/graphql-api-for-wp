<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

class CPTUtils
{
    /**
     * Get the description of the post, defined in the excerpt
     *
     * @param object $post
     * @return string
     */
    public static function getCustomPostDescription($post): string
    {
        return strip_tags($post->post_excerpt ?? '');
    }
}
