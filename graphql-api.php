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

define('GRAPHQL_BY_POP_PLUGIN_DIR', dirname(__FILE__));
define('GRAPHQL_BY_POP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GRAPHQL_BY_POP_VERSION', '0.1');

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Configure the plugin. This defines hooks to set environment variables, so must be executed
// before those hooks are triggered for first time (in ComponentConfiguration classes)
\Leoloso\GraphQLByPoPWPPlugin\PluginConfiguration::initialize();

// Load the current plugin's Component and, with it, all components
\Leoloso\GraphQLByPoPWPPlugin\Component::initialize();

// Initialize PoP Engine through the Bootloader
\PoP\Engine\Bootloader\Initialization::init();

// Initialize this plugin
$plugin = new \Leoloso\GraphQLByPoPWPPlugin\Plugin();
$plugin->init();

// Functions to execute when activating/deactivating the plugin
\register_activation_hook(__FILE__, [$plugin, 'activate']);
\register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);
