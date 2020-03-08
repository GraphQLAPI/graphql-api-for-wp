<?php
/*
Plugin Name: GraphQL by PoP
Plugin URI: https://github.com/leoloso/graphql-by-pop-wp-plugin
Description: GraphQL server for WordPress, implemented through PoP
Version: 1.0.0
Requires at least: 5.0
Requires PHP: 7.1
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-by-pop
Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}
define('GRAPHQL_BY_POP_PLUGIN_DIR', dirname(__FILE__));
define('GRAPHQL_BY_POP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GRAPHQL_BY_POP_VERSION', '0.1');

// Load Composer’s autoloader
require_once (__DIR__.'/vendor/autoload.php');

// Load the "must-use" plugin to boot PoP
require_once (__DIR__.'/wp-content/mu-plugins/engine-wp-bootloader/pop-engine-wp-bootloader.php');

// Initialize this plugin
(new \Leoloso\GraphQLByPoPWPPlugin\Plugin())->init();
