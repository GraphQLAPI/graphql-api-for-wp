<?php
/*
Plugin Name: GraphQL API for WordPress
Plugin URI: https://github.com/GraphQLAPI/graphql-api
Description: Transform your WordPress site into a GraphQL server
Version: 0.1.0
Requires at least: 5.0
Requires PHP: 7.1
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

use PoP\Engine\ComponentLoader;

define('GRAPHQL_API_PLUGIN_FILE', __FILE__);
define('GRAPHQL_API_DIR', dirname(__FILE__));
define('GRAPHQL_API_URL', plugin_dir_url(__FILE__));
define('GRAPHQL_BY_POP_VERSION', '0.1.0');

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Plugin instance
$plugin = new \GraphQLAPI\GraphQLAPI\Plugin();

// Set-up is executed immediately
$plugin->setup();

/**
 * Wait until "plugins_loaded" to initialize the plugin, because:
 *
 * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
 * - Allow other plugins to inject their own functionality
 *
 * Execute before any other GraphQL plugin
 */
add_action('plugins_loaded', function () use ($plugin) {
    // Boot all PoP components, from this plugin and all extensions
    ComponentLoader::bootComponents();
    // Initialize this plugin
    $plugin->initialize();
}, 0);
