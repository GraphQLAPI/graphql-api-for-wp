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

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\ComponentLoader;

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

// Component configuration
$componentClassConfiguration = [
    \PoP\Engine\Component::class => [
        \PoP\Engine\Environment::ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE => false,
    ],
];

// Component classes enabled/disabled by module
$moduleRegistry = ModuleRegistryFacade::getInstance();
$maybeSkipSchemaModuleComponentClasses = [
    ModuleResolver::DIRECTIVE_SET_CONVERT_LOWER_UPPERCASE => [
        \PoP\UsefulDirectives\Component::class,
    ],
    ModuleResolver::SCHEMA_POST_TYPE => [
        \PoP\PostMediaWP\Component::class,
        \PoP\PostMedia\Component::class,
        \PoP\PostMetaWP\Component::class,
        \PoP\PostMeta\Component::class,
        \PoP\PostsWP\Component::class,
        \PoP\Posts\Component::class,
    ],
    ModuleResolver::SCHEMA_COMMENT_TYPE => [
        \PoP\CommentMetaWP\Component::class,
        \PoP\CommentMeta\Component::class,
        \PoP\CommentsWP\Component::class,
        \PoP\Comments\Component::class,
    ],
    ModuleResolver::SCHEMA_USER_TYPE => [
        \PoP\UserMetaWP\Component::class,
        \PoP\UserMeta\Component::class,
        \PoP\UsersWP\Component::class,
        \PoP\Users\Component::class,
        \PoP\UserRolesWP\Component::class,
        \PoP\UserRoles\Component::class,
        \PoP\UserState\Component::class,
    ],
    ModuleResolver::SCHEMA_PAGE_TYPE => [
        \PoP\PagesWP\Component::class,
        \PoP\Pages\Component::class,
    ],
    ModuleResolver::SCHEMA_MEDIA_TYPE => [
        \PoP\PostMediaWP\Component::class,
        \PoP\PostMedia\Component::class,
        \PoP\MediaWP\Component::class,
        \PoP\Media\Component::class,
    ],
    ModuleResolver::SCHEMA_TAXONOMY_TYPE => [
        \PoP\TaxonomiesWP\Component::class,
        \PoP\Taxonomies\Component::class,
        \PoP\TaxonomyMetaWP\Component::class,
        \PoP\TaxonomyMeta\Component::class,
        \PoP\TaxonomyQueryWP\Component::class,
        \PoP\TaxonomyQuery\Component::class,
    ],
];
$skipSchemaModuleComponentClasses = array_filter(
    $maybeSkipSchemaModuleComponentClasses,
    function ($module) use ($moduleRegistry) {
        return !$moduleRegistry->isModuleEnabled($module);
    },
    ARRAY_FILTER_USE_KEY
);
$skipSchemaComponentClasses = GeneralUtils::arrayFlatten(array_values($skipSchemaModuleComponentClasses));

// Initialize the plugin's Component and, with it, all its dependencies from PoP
ComponentLoader::initializeComponents(
    [
        \GraphQLAPI\GraphQLAPI\Component::class,
    ],
    $componentClassConfiguration,
    $skipSchemaComponentClasses
);

// Boot all PoP components
ComponentLoader::bootComponents();

/**
 * Wait until "plugins_loaded" to initialize the plugin, because:
 *
 * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
 * - Allow other plugins to inject their own functionality
 */
add_action('plugins_loaded', function () use ($plugin) {
    $plugin->initialize();
});
