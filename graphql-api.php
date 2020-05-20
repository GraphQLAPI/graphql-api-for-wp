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

// Set all the pre-required environment variables, before loading all other modules
require_once(__DIR__ . '/environment.php');

// Load Composer’s autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Initialize all components
$componentClasses = [
    \PoP\CommentMetaWP\Component::class,
    \PoP\GraphQL\Component::class,
    \PoP\MediaWP\Component::class,
    \PoP\PagesWP\Component::class,
    \PoP\PostMediaWP\Component::class,
    \PoP\PostMetaWP\Component::class,
    \PoP\TaxonomyQueryWP\Component::class,
    \PoP\UserRolesAccessControl\Component::class,
    \PoP\UserRolesWP\Component::class,
    \PoP\UserStateWP\Component::class,
    \PoP\UserMetaWP\Component::class,
    \PoP\UsefulDirectives\Component::class,
    \PoP\FieldDeprecationByDirective\Component::class,
];
foreach ($componentClasses as $componentClass) {
    $componentClass::initialize();
}

// Configure the plugin. This defines hooks to set environment variables, so must be executed
// before those hooks are triggered for first time (in ComponentConfiguration classes)
\Leoloso\GraphQLByPoPWPPlugin\PluginConfiguration::init();

// Load the current plugin's Component
\Leoloso\GraphQLByPoPWPPlugin\Component::init();

// Load the "must-use" plugin to boot PoP
require_once(__DIR__ . '/wp-content/mu-plugins/engine-wp-bootloader/pop-engine-wp-bootloader.php');

// Initialize this plugin
$plugin = new \Leoloso\GraphQLByPoPWPPlugin\Plugin();
$plugin->init();

// Functions to execute when activating/deactivating the plugin
\register_activation_hook(__FILE__, [$plugin, 'activate']);
\register_deactivation_hook(__FILE__, [$plugin, 'deactivate']);
