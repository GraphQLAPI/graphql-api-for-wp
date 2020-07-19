<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\Engine\ComponentLoader;
use GraphQLAPI\GraphQLAPI\PluginConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLEndpointPostType;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLPersistedQueryPostType;
use GraphQLAPI\GraphQLAPI\Admin\TableActions\ModuleListTableAction;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLCacheControlListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLAccessControlListPostType;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLFieldDeprecationListPostType;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserRolesBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserStateBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlDisableAccessBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserCapabilitiesBlock;

class Plugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Plugin set-up, executed immediately when loading the plugin
     *
     * @return void
     */
    public function setup(): void
    {
        /**
         * Wait until "plugins_loaded" to initialize the plugin, because:
         *
         * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
         * - Allow other plugins to inject their own functionality
         *
         * Execute before any other GraphQL plugin
         */
        add_action('plugins_loaded', [$this, 'initialize'], 5);

        // Functions to execute when activating/deactivating the plugin
        \register_activation_hook(\GRAPHQL_API_PLUGIN_FILE, [$this, 'activate']);
        \register_deactivation_hook(\GRAPHQL_API_PLUGIN_FILE, [$this, 'deactivate']);

        // Configure the plugin. This defines hooks to set environment variables,
        // so must be executed
        // before those hooks are triggered for first time
        // (in ComponentConfiguration classes)
        PluginConfiguration::initialize();

        // Component configuration
        $componentClassConfiguration = PluginConfiguration::getComponentClassConfiguration();
        $skipSchemaComponentClasses = PluginConfiguration::getSkippingSchemaComponentClasses();

        // Initialize the plugin's Component and, with it,
        // all its dependencies from PoP
        ComponentLoader::initializeComponents(
            [
                \GraphQLAPI\GraphQLAPI\Component::class,
            ],
            $componentClassConfiguration,
            $skipSchemaComponentClasses
        );
    }

    /**
     * Plugin initialization, executed on hook "plugins_loaded"
     * to wait for all extensions to be loaded
     *
     * @return void
     */
    public function initialize(): void
    {
        // Boot all PoP components, from this plugin and all extensions
        ComponentLoader::bootComponents();

        $instanceManager = InstanceManagerFacade::getInstance();
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        /**
         * Initialize classes for the admin panel
         */
        if (\is_admin()) {
            /**
             * Execute the ModuleListTable enable/disable modules immediately,
             * so that CPTs are enabled/disabled
             */
            $moduleListTable = $instanceManager->getInstance(ModuleListTableAction::class);
            $moduleListTable->maybeProcessAction();

            /**
             * Initialize all the services
             */
            $menuServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\Menus');
            foreach ($menuServiceClasses as $serviceClass) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
            $endpointResolverServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\EndpointResolvers');
            foreach ($endpointResolverServiceClasses as $serviceClass) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
            $developmentServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\Development');
            foreach ($developmentServiceClasses as $serviceClass) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
        }

        /**
         * Taxonomies must be initialized before Post Types
         */
        $taxonomyServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Taxonomies');
        foreach ($taxonomyServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
        /**
         * Initialize Post Types manually to control in what order they are added to the menu
         */
        $postTypeServiceClassModules = [
            GraphQLEndpointPostType::class => FunctionalityModuleResolver::CUSTOM_ENDPOINTS,
            GraphQLPersistedQueryPostType::class => FunctionalityModuleResolver::PERSISTED_QUERIES,
            GraphQLSchemaConfigurationPostType::class => FunctionalityModuleResolver::SCHEMA_CONFIGURATION,
            GraphQLAccessControlListPostType::class => FunctionalityModuleResolver::ACCESS_CONTROL,
            GraphQLCacheControlListPostType::class => FunctionalityModuleResolver::CACHE_CONTROL,
            GraphQLFieldDeprecationListPostType::class => FunctionalityModuleResolver::FIELD_DEPRECATION,
        ];
        foreach ($postTypeServiceClassModules as $serviceClass => $module) {
            // Check that the corresponding module is enabled
            if ($moduleRegistry->isModuleEnabled($module)) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
        }
        /**
         * Editor Scripts
         * They are all used to show the Welcome Guide
         */
        if ($moduleRegistry->isModuleEnabled(FunctionalityModuleResolver::WELCOME_GUIDES)) {
            $editorScriptServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\EditorScripts');
            foreach ($editorScriptServiceClasses as $serviceClass) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
        }
        /**
         * Blocks
         * The GraphiQL Block may be overriden to GraphiQLWithExplorerBlock
         */
        $blockServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Blocks', false);
        foreach ($blockServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
        /**
         * Access Control Nested Blocks
         * Register them one by one, as to disable them if module is disabled
         */
        $accessControlRuleBlockServiceClassModules = [
            AccessControlDisableAccessBlock::class => FunctionalityModuleResolver::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            AccessControlUserStateBlock::class => FunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_STATE,
            AccessControlUserRolesBlock::class => FunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_ROLES,
            AccessControlUserCapabilitiesBlock::class => FunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
        ];
        foreach ($accessControlRuleBlockServiceClassModules as $serviceClass => $module) {
            if ($moduleRegistry->isModuleEnabled($module)) {
                $instanceManager->getInstance($serviceClass)->initialize();
            }
        }
        /**
         * Block categories
         */
        $blockCategoryServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\BlockCategories');
        foreach ($blockCategoryServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
    }

    /**
     * Get permalinks to work when activating the plugin
     *
     * @see    https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
     * @return void
     */
    public function activate(): void
    {
        // First, initialize all the custom post types
        $instanceManager = InstanceManagerFacade::getInstance();
        $postTypeObjects = array_map(
            function ($serviceClass) use ($instanceManager) {
                return $instanceManager->getInstance($serviceClass);
            },
            ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\PostTypes')
        );
        foreach ($postTypeObjects as $postTypeObject) {
            $postTypeObject->registerPostType();
        }

        // Then, flush rewrite rules
        \flush_rewrite_rules();

        // Initialize the timestamp
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->storeTimestamp();
    }

    /**
     * Remove permalinks when deactivating the plugin
     *
     * @see    https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     * @return void
     */
    public function deactivate(): void
    {
        // First, unregister the post type, so the rules are no longer in memory.
        $instanceManager = InstanceManagerFacade::getInstance();
        $postTypeObjects = array_map(
            function ($serviceClass) use ($instanceManager) {
                return $instanceManager->getInstance($serviceClass);
            },
            ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\PostTypes')
        );
        foreach ($postTypeObjects as $postTypeObject) {
            $postTypeObject->unregisterPostType();
        }

        // Then, clear the permalinks to remove the post type's rules from the database.
        \flush_rewrite_rules();

        // Remove the timestamp
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->removeTimestamp();
    }
}
