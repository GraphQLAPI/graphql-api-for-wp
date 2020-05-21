<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

class GeneralUtils
{
    /**
     * Convert a string with dashes into camelCase mode
     *
     * @see https://stackoverflow.com/a/2792045
     * @param [type] $string
     * @param boolean $capitalizeFirstCharacter
     * @return string
     */
    public static function dashesToCamelCase($string, $capitalizeFirstCharacter = false): string
    {
        $str = str_replace('-', '', ucwords($string, '-'));
        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }
        return $str;
    }
}
