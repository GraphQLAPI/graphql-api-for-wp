<?php
namespace Leoloso\GraphQLByPoPWPPlugin;

class Environment
{
    public const ADD_EXCERPT_AS_DESCRIPTION = 'ADD_EXCERPT_AS_DESCRIPTION';
    public const GROUP_FIELDS_UNDER_TYPE_FOR_PRINT = 'GROUP_FIELDS_UNDER_TYPE_FOR_PRINT';

    /**
     * Print the excerpt as description in the custom post types
     *
     * @return boolean
     */
    public static function addExcerptAsDescription(): bool
    {
        return isset($_ENV[self::ADD_EXCERPT_AS_DESCRIPTION]) ? strtolower($_ENV[self::ADD_EXCERPT_AS_DESCRIPTION]) == "true" : true;
    }

    /**
     * Group the fields under the type when printing it for the user
     *
     * @return boolean
     */
    public static function groupFieldsUnderTypeForPrint(): bool
    {
        return isset($_ENV[self::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT]) ? strtolower($_ENV[self::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT]) == "true" : true;
    }
}

