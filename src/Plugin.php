<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLEndpointPostType;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLPersistedQueryPostType;
use GraphQLAPI\GraphQLAPI\Admin\TableActions\ModuleListTableAction;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLCacheControlListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLAccessControlListPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLFieldDeprecationListPostType;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserRolesBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserStateBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlDisableAccessBlock;
use GraphQLAPI\GraphQLAPI\Blocks\AccessControlRuleBlocks\AccessControlUserCapabilitiesBlock;
use GraphQLAPI\GraphQLAPI\Clients\GraphiQLClient;

class Plugin
{
    /**
     * Plugin's namespace
     */
    public const NAMESPACE = __NAMESPACE__;

    public function initialize(): void
    {
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
            GraphQLEndpointPostType::class => ModuleResolver::CUSTOM_ENDPOINTS,
            GraphQLPersistedQueryPostType::class => ModuleResolver::PERSISTED_QUERIES,
            GraphQLSchemaConfigurationPostType::class => ModuleResolver::SCHEMA_CONFIGURATION,
            GraphQLAccessControlListPostType::class => ModuleResolver::ACCESS_CONTROL,
            GraphQLCacheControlListPostType::class => ModuleResolver::CACHE_CONTROL,
            GraphQLFieldDeprecationListPostType::class => ModuleResolver::FIELD_DEPRECATION,
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
        if ($moduleRegistry->isModuleEnabled(ModuleResolver::WELCOME_GUIDES)) {
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
            AccessControlDisableAccessBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_DISABLE_ACCESS,
            AccessControlUserStateBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_USER_STATE,
            AccessControlUserRolesBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_USER_ROLES,
            AccessControlUserCapabilitiesBlock::class => ModuleResolver::ACCESS_CONTROL_RULE_USER_CAPABILITIES,
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
        /**
         * Clients
         */
        $clientServiceClasses = ContainerBuilderUtils::getServiceClassesUnderNamespace(__NAMESPACE__ . '\\Clients');
        foreach ($clientServiceClasses as $serviceClass) {
            $instanceManager->getInstance($serviceClass)->initialize();
        }
    }

    /**
     * Get permalinks to work when activating the plugin
     * @see https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
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
    }

    /**
     * Remove permalinks when deactivating the plugin
     * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
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
    }
}
