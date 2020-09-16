<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\Engine\ComponentLoader;
use GraphQLAPI\GraphQLAPI\PluginConfiguration;
use GraphQLAPI\GraphQLAPI\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Admin\Menus\AbstractMenu;
use GraphQLAPI\GraphQLAPI\PostTypes\AbstractPostType;
use GraphQLAPI\GraphQLAPI\Taxonomies\AbstractTaxonomy;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\ModulesMenuPage;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLEndpointPostType;
use GraphQLAPI\GraphQLAPI\EditorScripts\AbstractEditorScript;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLPersistedQueryPostType;
use GraphQLAPI\GraphQLAPI\Admin\TableActions\ModuleListTableAction;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLCacheControlListPostType;
use GraphQLAPI\GraphQLAPI\EndpointResolvers\AbstractEndpointResolver;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLAccessControlListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLFieldDeprecationListPostType;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserRolesBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserStateBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlDisableAccessBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserCapabilitiesBlock;

class Plugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    /**
     * Plugin set-up, executed immediately when loading the plugin.
     * There are three stages for this plugin, and for each extension plugin:
     * `setup`, `initialize` and `boot`.
     *
     * This is because:
     *
     * - The plugin must execute its logic before the extensions
     * - The services can't be booted before all services have been initialized
     *
     * To attain the needed order, we execute them using hook "plugins_loaded":
     *
     * 1. GraphQL API => setup(): immediately
     * 2. GraphQL API extensions => setup(): priority 0
     * 3. GraphQL API => initialize(): priority 5
     * 4. GraphQL API extensions => initialize(): priority 10
     * 5. GraphQL API => boot(): priority 15
     * 6. GraphQL API extensions => boot(): priority 20
     *
     * @return void
     */
    public function setup(): void
    {
        // Functions to execute when activating/deactivating the plugin
        \register_deactivation_hook(\GRAPHQL_API_PLUGIN_FILE, [$this, 'deactivate']);
        /**
         * PoP depends on hook "init" to set-up the endpoint rewrite,
         * as in function `addRewriteEndpoints` in `AbstractEndpointHandler`
         * However, activating the plugin takes place AFTER hooks "plugins_loaded"
         * and "init". Hence, the code cannot flush the rewrite_rules when the plugin
         * is activated, and any non-default GraphQL endpoint is not set.
         *
         * The solution (hack) is to check if the plugin has just been installed,
         * and then apply the logic, on every request in the admin!
         *
         * @see https://developer.wordpress.org/reference/functions/register_activation_hook/#process-flow
         */
        $isPluginJustActivatedOptionName = 'graphql-api-activated-plugin';
        \register_activation_hook(
            \GRAPHQL_API_PLUGIN_FILE,
            function () use ($isPluginJustActivatedOptionName): void {
                // Flag to indicate that the plugin has been activated
                \add_option($isPluginJustActivatedOptionName, true);
                // This is the proper activation logic
                $this->activate();
            }
        );
        \add_action(
            'admin_init',
            function () use ($isPluginJustActivatedOptionName): void {
                // If the flag is there, it's the first screen after activating the plugin
                if (\is_admin() && \get_option($isPluginJustActivatedOptionName) == true) {
                    // Delete the flag
                    \delete_option($isPluginJustActivatedOptionName);
                    // Required logic after plugin is activated
                    \flush_rewrite_rules();
                }
            }
        );

        /**
         * Wait until "plugins_loaded" to initialize the plugin, because:
         *
         * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
         * - Allow other plugins to inject their own functionality
         */
        \add_action('plugins_loaded', [$this, 'initialize'], 5);
        \add_action('plugins_loaded', [$this, 'boot'], 15);
    }

    /**
     * Plugin initialization, executed on hook "plugins_loaded"
     * to wait for all extensions to be loaded
     *
     * @return void
     */
    public function initialize(): void
    {
        /**
         * Watch out! If we are in the Modules page and enabling/disabling
         * a module, then already take that new state!
         * This is because `maybeProcessAction`, which is where modules are
         * enabled/disabled, must be executed before PluginConfiguration::initialize(),
         * which is where the plugin reads if a module is enabled/disabled as to
         * set the environment constants.
         *
         * This is mandatory, because only when it is enabled, can a module
         * have its state persisted when calling `flush_rewrite`
         */
        if (\is_admin()) {
            // We can't use the InstanceManager, since at this stage it hasn't
            // been initialized yet
            // We can create a new instances of ModulesMenuPage because
            // its instantiation produces no side-effects
            $modulesMenuPage = new ModulesMenuPage();
            if (isset($_GET['page']) && $_GET['page'] == $modulesMenuPage->getScreenID()) {
                // Instantiating ModuleListTableAction DOES have side-effects,
                // but they are needed, and won't be repeated when instantiating
                // the class through the Container Builder
                $moduleListTable = new ModuleListTableAction();
                $moduleListTable->maybeProcessAction();
            }
        }

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
    public function boot(): void
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
             * Initialize all the services
             */
            $menuServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\Menus');
            foreach ($menuServiceClasses as $serviceClass) {
                /**
                 * @var AbstractMenu
                 */
                $service = $instanceManager->getInstance($serviceClass);
                $service->initialize();
            }
            $endpointResolverServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Admin\\EndpointResolvers');
            foreach ($endpointResolverServiceClasses as $serviceClass) {
                /**
                 * @var AbstractEndpointResolver
                 */
                $service = $instanceManager->getInstance($serviceClass);
                $service->initialize();
            }
        }

        /**
         * Taxonomies must be initialized before Post Types
         */
        $taxonomyServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Taxonomies');
        foreach ($taxonomyServiceClasses as $serviceClass) {
            /**
             * @var AbstractTaxonomy
             */
            $service = $instanceManager->getInstance($serviceClass);
            $service->initialize();
        }
        /**
         * Initialize Post Types manually to control in what order they are added to the menu
         */
        $postTypeServiceClassModules = [
            GraphQLEndpointPostType::class => EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
            GraphQLPersistedQueryPostType::class => EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
            GraphQLSchemaConfigurationPostType::class => SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
            GraphQLAccessControlListPostType::class => AccessControlFunctionalityModuleResolver::ACCESS_CONTROL,
            GraphQLCacheControlListPostType::class => PerformanceFunctionalityModuleResolver::CACHE_CONTROL,
            GraphQLFieldDeprecationListPostType::class => VersioningFunctionalityModuleResolver::FIELD_DEPRECATION,
        ];
        foreach ($postTypeServiceClassModules as $serviceClass => $module) {
            // Check that the corresponding module is enabled
            if ($moduleRegistry->isModuleEnabled($module)) {
                /**
                 * @var AbstractPostType
                 */
                $service = $instanceManager->getInstance($serviceClass);
                $service->initialize();
            }
        }
        /**
         * Editor Scripts
         * They are all used to show the Welcome Guide
         */
        if ($moduleRegistry->isModuleEnabled(UserInterfaceFunctionalityModuleResolver::WELCOME_GUIDES)) {
            $editorScriptServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\EditorScripts');
            foreach ($editorScriptServiceClasses as $serviceClass) {
                /**
                 * @var AbstractEditorScript
                 */
                $service = $instanceManager->getInstance($serviceClass);
                $service->initialize();
            }
        }
        /**
         * Blocks
         * The GraphiQL Block may be overriden to GraphiQLWithExplorerBlock
         */
        $blockServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Blocks', false);
        foreach ($blockServiceClasses as $serviceClass) {
            /**
             * @var AbstractBlock
             */
            $service = $instanceManager->getInstance($serviceClass);
            $service->initialize();
        }
        /**
         * Access Control Nested Blocks
         * Register them one by one, as to disable them if module is disabled
         */
        $accessControlRuleBlockServiceClassModules = [
            AccessControlDisableAccessBlock::class => AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            AccessControlUserStateBlock::class => AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_STATE,
            AccessControlUserRolesBlock::class => AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_ROLES,
            AccessControlUserCapabilitiesBlock::class => AccessControlFunctionalityModuleResolver::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
        ];
        foreach ($accessControlRuleBlockServiceClassModules as $serviceClass => $module) {
            if ($moduleRegistry->isModuleEnabled($module)) {
                /**
                 * @var AbstractBlock
                 */
                $service = $instanceManager->getInstance($serviceClass);
                $service->initialize();
            }
        }
        /**
         * Block categories
         */
        $blockCategoryServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\BlockCategories');
        foreach ($blockCategoryServiceClasses as $serviceClass) {
            /**
             * @var AbstractBlockCategory
             */
            $service = $instanceManager->getInstance($serviceClass);
            $service->initialize();
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
            function ($serviceClass) use ($instanceManager): AbstractPostType {
                /**
                 * @var AbstractPostType
                 */
                $postTypeObject = $instanceManager->getInstance($serviceClass);
                return $postTypeObject;
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
