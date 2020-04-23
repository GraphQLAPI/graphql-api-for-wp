<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\General;

class URLParamHelpers
{
    /**
     * Reproduce exactly the `encodeURIComponent` JavaScript function
     * Taken from https://stackoverflow.com/a/1734255
     *
     * @param string $str
     * @return string
     */
    public static function encodeURIComponent(string $str): string
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return \strtr(\rawurlencode($str), $revert);
    }
}
