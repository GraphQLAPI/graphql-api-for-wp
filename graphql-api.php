<?php
/*
Plugin Name: GraphQL API
Plugin URI: https://github.com/GraphQLAPI/graphql-api
Description: Transform your WordPress site into a GraphQL server.
Version: 0.1.1
Requires at least: 5.4
Requires PHP: 7.2.5
Author: Leonardo Losoviz
Author URI: https://leoloso.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: graphql-api
Domain Path: /languages
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('GRAPHQL_API_PLUGIN_FILE', __FILE__);
define('GRAPHQL_API_DIR', dirname(__FILE__));
define('GRAPHQL_API_URL', plugin_dir_url(__FILE__));
define('GRAPHQL_API_VERSION', '0.1.0');

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Create and set-up the plugin instance
(new \GraphQLAPI\GraphQLAPI\Plugin())->setup();
