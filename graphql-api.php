<?php
/*
Plugin Name: GraphQL API for WordPress
Plugin URI: https://github.com/leoloso/graphql-api-wp-plugin
Description: Transform your WordPress site into a GraphQL server
Version: 1.0.0
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

define('GRAPHQL_API_DIR', dirname(__FILE__));
define('GRAPHQL_API_URL', plugin_dir_url(__FILE__));
define('GRAPHQL_BY_POP_VERSION', '0.1');

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Plugin instance
$plugin = new \GraphQLAPI\GraphQLAPI\Plugin();

// Functions to execute when activating/deactivating the plugin
\register_activation_hook(__FILE__, [$plugin, 'activate']);
\register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);

// Configure the plugin. This defines hooks to set environment variables, so must be executed
// before those hooks are triggered for first time (in ComponentConfiguration classes)
\GraphQLAPI\GraphQLAPI\PluginConfiguration::initialize();

// Initialize the plugin's Component and, with it, all dependencies from PoP
\GraphQLAPI\GraphQLAPI\Component::initialize();

// Initialize the PoP Engine through the Bootloader
\PoP\Engine\Bootloader::bootComponents();

/**
 * Wait until "plugins_loaded" to initialize the plugin, because:
 *
 * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
 * - Allow other plugins to inject their own functionality
 */
add_action('plugins_loaded', function () use ($plugin) {
    $plugin->initialize();
});
