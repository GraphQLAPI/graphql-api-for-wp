<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

class Environment
{
    public const ADD_EXCERPT_AS_DESCRIPTION = 'ADD_EXCERPT_AS_DESCRIPTION';
    /**
     * Print the excerpt as description in the custom post types
     *
     * @return boolean
     */
    public static function addExcerptAsDescription(): bool
    {
        return isset($_ENV[self::ADD_EXCERPT_AS_DESCRIPTION]) ? strtolower($_ENV[self::ADD_EXCERPT_AS_DESCRIPTION]) == "true" : true;
    }
}

