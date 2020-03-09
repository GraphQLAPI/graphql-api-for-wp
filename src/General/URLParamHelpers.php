<?php
namespace Leoloso\GraphQLByPoPWPPlugin\General;

class URLParamHelpers {

    /**
	 * Reproduce exactly the `encodeURIComponent` JavaScript function
	 * Taken from https://stackoverflow.com/a/1734255
	 *
	 * @param [type] $str
	 * @return void
	 */
	public static function encodeURIComponent($str) {
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
		return strtr(rawurlencode($str), $revert);
	}
}
