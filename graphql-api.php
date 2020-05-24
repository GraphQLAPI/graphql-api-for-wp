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

// Configure the plugin. This defines hooks to set environment variables, so must be executed
// before those hooks are triggered for first time (in ComponentConfiguration classes)
\GraphQLAPI\GraphQLAPI\PluginConfiguration::initialize();

// Initialize the plugin's Component and, with it, all dependencies from PoP
\GraphQLAPI\GraphQLAPI\Component::initialize();

// Initialize the PoP Engine through the Bootloader
\PoP\Engine\Bootloader::bootComponents();

// Initialize this plugin
$plugin = new \GraphQLAPI\GraphQLAPI\Plugin();
$plugin->initialize();

// Functions to execute when activating/deactivating the plugin
\register_activation_hook(__FILE__, [$plugin, 'activate']);
\register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);

/**
 * Testing https://github.com/afragen/wp-dependency-installe
 */
$config = [
    [
      'name'    => 'Query Monitor',
      'host'    => 'wordpress',
      'slug'    => 'query-monitor/query-monitor.php',
      'uri'     => 'https://wordpress.org/plugins/query-monitor/',
      'required' => true,
    ],
    // [
    //   'name'     => 'Debug Bar',
    //   'host'     => 'wordpress',
    //   'slug'     => 'debug-bar/debug-bar.php',
    //   'uri'      => 'https://wordpress.org/plugins/debug-bar/',
    //   'optional' => true,
    // ],
    [
      'name'     => 'Yoast',
      'host'     => 'wordpress',
      'slug'     => 'wordpress-seo/wp-seo.php',
      'uri'      => 'https://wordpress.org/plugins/wordpress-seo/',
      'required' => true,
    ],
    // [
    //     'name' => 'WPGraphQL',
    //     'host' => 'github',
    //     'slug' => 'wp-graphql/wp-graphql.php',
    //     'uri' => 'wp-graphql/wp-graphql',
    //     'branch' => 'master',
    //     'optional' => true,
    //     'token' => null,
    // ],
    [
        'name' => 'graphql-api',
        'host' => 'github',
        'slug' => 'graphql-api/graphql-api.php',
        'uri' => 'GraphQLAPI/graphql-api',
        'branch' => 'master',
        'required' => true,
        // 'token' => 'f0a3b0d079796022fc94117ea065f57fafe88bd5',
    ],
];

// Disable "Required Plugin" label ( plugin_actions_links )
\add_filter( 'wp_dependency_required_label', '__return_false' );

// Disable "Required by" row ( plugin_row_meta ).
\add_filter( 'wp_dependency_required_row_meta', '__return_false' );

// Restore "deactivate" and "delete" links ( plugin_actions_links ).
\add_filter( 'wp_dependency_unset_action_links', '__return_false' );

\add_filter(
    'wp_dependency_dismiss_label',
    function( $label, $source ) {
        $label = basename(__DIR__) !== $source ? $label : __( 'Group Plugin Installer', 'group-plugin-installer' );
        return $label;
    },
    10,
2
);

WP_Dependency_Installer::instance( __DIR__ )->register( $config )->run();
