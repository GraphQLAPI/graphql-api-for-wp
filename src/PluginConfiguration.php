<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\APIEndpoints\EndpointUtils;
use GraphQLAPI\GraphQLAPI\Environment;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\ComponentModel\Misc\GeneralUtils;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;
use GraphQLAPI\GraphQLAPI\Admin\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PoP\CacheControl\Environment as CacheControlEnvironment;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\APIEndpointsForWP\Environment as APIEndpointsForWPEnvironment;
use PoP\GraphQLClientsForWP\Environment as GraphQLClientsForWPEnvironment;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\CacheControl\ComponentConfiguration as CacheControlComponentConfiguration;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\APIEndpointsForWP\ComponentConfiguration as APIEndpointsForWPComponentConfiguration;
use PoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;

/**
 * Sets the configuration in all the PoP components.
 *
 * To set the value for properties, it uses this order:
 *
 * 1. Retrieve it as an environment value, if defined
 * 2. Retrieve as a constant `GRAPHQL_API_...` from wp-config.php, if defined
 * 3. Retrieve it from the user settings, if stored
 * 4. Use the default value
 *
 * If a slug is set or updated in the environment variable or wp-config constant,
 * it is necessary to flush the rewrite rules for the change to take effect.
 * For that, on the WordPress admin, go to Settings => Permalinks and click on Save changes
 */
class PluginConfiguration
{
    protected static $normalizedOptionValuesCache;

    /**
     * Initialize all configuration
     *
     * @return array
     */
    public static function initialize(): void
    {
        self::mapEnvVariablesToWPConfigConstants();
        self::defineEnvironmentConstantsFromSettings();
    }

    /**
     * Get the values from the form submitted to options.php, and normalize them
     *
     * @return array
     */
    protected static function getNormalizedOptionValues(): array
    {
        if (is_null(self::$normalizedOptionValuesCache)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
            // Obtain the values from the POST and normalize them
            $value = $_POST[SettingsMenuPage::SETTINGS_FIELD];
            self::$normalizedOptionValuesCache = $settingsMenuPage->normalizeSettings($value);
        }
        return self::$normalizedOptionValuesCache;
    }

    /**
     * If we are in options.php, already set the new slugs in the hook,
     * so that the EndpointHandler's `addRewriteEndpoints` (executed on `init`)
     * adds the rewrite with the new slug, which will be persisted on
     * flushing the rewrite rules
     *
     * @return mixed
     */
    protected static function maybeOverrideValueFromForm($value, string $module, string $option)
    {
        global $pagenow;
        if ($pagenow == 'options.php') {
            $value = self::getNormalizedOptionValues();
            // Return the specific value to this module/option
            $moduleRegistry = ModuleRegistryFacade::getInstance();
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $optionName = $moduleResolver->getSettingOptionName($module, $option);
            return $value[$optionName];
        }
        return $value;
    }

    /**
     * Process the "URL path" option values
     *
     * @param string $value
     * @param string $module
     * @param string $option
     * @return string
     */
    protected static function getURLPathSettingValue(
        string $value,
        string $module,
        string $option
    ): string {
        // Make sure the path has a "/" on both ends
        $value = EndpointUtils::slashURI($value);

        // If we are on options.php, use the value submitted to the form,
        // so it's updated before doing `add_rewrite_endpoint` and `flush_rewrite_rules`
        return self::maybeOverrideValueFromForm($value, $module, $option);
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     *
     * @return array
     */
    protected static function defineEnvironmentConstantsFromSettings(): void
    {
        // All the environment variables to override
        $mappings = [
            // GraphQL single endpoint slug
            [
                'class' => APIEndpointsForWPComponentConfiguration::class,
                'envVariable' => APIEndpointsForWPEnvironment::GRAPHQL_API_ENDPOINT,
                'module' => ModuleResolver::SINGLE_ENDPOINT,
                'option' => ModuleResolver::OPTION_PATH,
                'callback' => function ($value) {
                    return self::getURLPathSettingValue(
                        $value,
                        ModuleResolver::SINGLE_ENDPOINT,
                        ModuleResolver::OPTION_PATH
                    );
                },
            ],
            // GraphiQL client slug
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::GRAPHIQL_CLIENT_ENDPOINT,
                'module' => ModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'option' => ModuleResolver::OPTION_PATH,
                'callback' => function ($value) {
                    return self::getURLPathSettingValue(
                        $value,
                        ModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                        ModuleResolver::OPTION_PATH
                    );
                },
            ],
            // Voyager client slug
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::VOYAGER_CLIENT_ENDPOINT,
                'module' => ModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'option' => ModuleResolver::OPTION_PATH,
                'callback' => function ($value) {
                    return self::getURLPathSettingValue(
                        $value,
                        ModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                        ModuleResolver::OPTION_PATH
                    );
                },
            ],
            // Use private schema mode?
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE,
                'module' => ModuleResolver::PUBLIC_PRIVATE_SCHEMA,
                'option' => ModuleResolver::OPTION_MODE,
                'callback' => function ($value) {
                    // It is stored as string "private" in DB, and must be passed as bool `true` to component
                    return $value == SchemaModes::PRIVATE_SCHEMA_MODE;
                },
            ],
            // Enable individual access control for the schema mode?
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE,
                'module' => ModuleResolver::PUBLIC_PRIVATE_SCHEMA,
                'option' => ModuleResolver::OPTION_ENABLE_GRANULAR,
            ],
            // Use namespacing?
            [
                'class' => ComponentModelComponentConfiguration::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
                'module' => ModuleResolver::SCHEMA_NAMESPACING,
                'option' => ModuleResolver::OPTION_USE_NAMESPACING,
            ],
            // Cache-Control default max-age
            [
                'class' => CacheControlComponentConfiguration::class,
                'envVariable' => CacheControlEnvironment::DEFAULT_CACHE_CONTROL_MAX_AGE,
                'module' => ModuleResolver::CACHE_CONTROL,
                'option' => ModuleResolver::OPTION_MAX_AGE,
            ],
        ];
        // For each environment variable, see if its value has been saved in the settings
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        foreach ($mappings as $mapping) {
            $module = $mapping['module'];
            // If the corresponding module is not enabled, then do nothing
            if (!$moduleRegistry->isModuleEnabled($module)) {
                continue;
            }
            // If the environment value has been defined, or the constant in wp-config.php,
            // then do nothing, since they have priority
            $envVariable = $mapping['envVariable'];
            if (isset($_ENV[$envVariable]) || self::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            $hookName = ComponentConfigurationHelpers::getHookName(
                $mapping['class'],
                $envVariable
            );
            $option = $mapping['option'];
            $callback = $mapping['callback'];
            \add_filter(
                $hookName,
                function () use ($userSettingsManager, $module, $option, $callback) {
                    $value = $userSettingsManager->getSetting($module, $option);
                    if ($callback) {
                        return $callback($value);
                    }
                    return $value;
                }
            );
        }
    }

    /**
     * Map the environment variables from the components, to WordPress wp-config.php constants
     *
     * @return array
     */
    protected static function mapEnvVariablesToWPConfigConstants(): void
    {
        // All the environment variables to override
        $mappings = [
            [
                'class' => ComponentConfiguration::class,
                'envVariable' => Environment::ADD_EXCERPT_AS_DESCRIPTION,
            ],
            [
                'class' => APIEndpointsForWPComponentConfiguration::class,
                'envVariable' => APIEndpointsForWPEnvironment::GRAPHQL_API_ENDPOINT,
            ],
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::GRAPHIQL_CLIENT_ENDPOINT,
            ],
            [
                'class' => GraphQLClientsForWPComponentConfiguration::class,
                'envVariable' => GraphQLClientsForWPEnvironment::VOYAGER_CLIENT_ENDPOINT,
            ],
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::USE_PRIVATE_SCHEMA_MODE,
            ],
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE,
            ],
            [
                'class' => ComponentModelComponentConfiguration::class,
                'envVariable' => ComponentModelEnvironment::NAMESPACE_TYPES_AND_INTERFACES,
            ],
            [
                'class' => CacheControlComponentConfiguration::class,
                'envVariable' => CacheControlEnvironment::DEFAULT_CACHE_CONTROL_MAX_AGE,
            ],
        ];
        // For each environment variable, see if it has been defined as a wp-config.php constant
        foreach ($mappings as $mapping) {
            $class = $mapping['class'];
            $envVariable = $mapping['envVariable'];

            // If the environment value has been defined, then do nothing, since it has priority
            if (isset($_ENV[$envVariable])) {
                continue;
            }
            $hookName = ComponentConfigurationHelpers::getHookName(
                $class,
                $envVariable
            );

            \add_filter(
                $hookName,
                /**
                 * Override the value of an environment variable if it has been definedas a constant
                 * in wp-config.php, with the environment name prepended with "GRAPHQL_API_"
                 */
                function ($value) use ($envVariable) {
                    if (self::isWPConfigConstantDefined($envVariable)) {
                        return self::getWPConfigConstantValue($envVariable);
                    }
                    return $value;
                }
            );
        }
    }

    /**
     * Determine if the environment variable was defined as a constant in wp-config.php
     *
     * @return mixed
     */
    protected static function getWPConfigConstantValue(string $envVariable)
    {
        return constant(self::getWPConfigConstantName($envVariable));
    }

    /**
     * Determine if the environment variable was defined as a constant in wp-config.php
     *
     * @return string
     */
    protected static function isWPConfigConstantDefined(string $envVariable): bool
    {
        return defined(self::getWPConfigConstantName($envVariable));
    }

    /**
     * Constants defined in wp-config.php must start with this prefix to override GraphQL API environment variables
     *
     * @return string
     */
    protected static function getWPConfigConstantName($envVariable): string
    {
        return 'GRAPHQL_API_' . $envVariable;
    }

    /**
     * Provide the configuration for all components required in the plugin
     *
     * @return array
     */
    public static function getComponentClassConfiguration(): array
    {
        $componentClassConfiguration = [];
        self::addPredefinedComponentClassConfiguration($componentClassConfiguration);
        self::addBasedOnModuleComponentClassConfiguration($componentClassConfiguration);
        return $componentClassConfiguration;
    }

    /**
     * Add the fixed configuration for all components required in the plugin
     *
     * @return void
     */
    protected static function addPredefinedComponentClassConfiguration(array &$componentClassConfiguration): void
    {
        $componentClassConfiguration[\PoP\Engine\Component::class] = [
            \PoP\Engine\Environment::ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE => false,
        ];
        $componentClassConfiguration[\PoP\GraphQLClientsForWP\Component::class] = [
            \PoP\GraphQLClientsForWP\Environment::GRAPHQL_CLIENTS_COMPONENT_URL => \GRAPHQL_API_URL . 'vendor/getpop/graphql-clients-for-wp',
        ];
    }

    /**
     * Add configuration values if modules are enabled or disabled
     *
     * @return void
     */
    protected static function addBasedOnModuleComponentClassConfiguration(array &$componentClassConfiguration): void
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleToComponentClassConfigurationMappings = [
            [
                'module' => ModuleResolver::SINGLE_ENDPOINT,
                'condition' => false,
                'class' => \PoP\APIEndpointsForWP\Component::class,
                'envVariable' => \PoP\APIEndpointsForWP\Environment::GRAPHQL_API_ENDPOINT,
                'value' => '',
            ],
            [
                'module' => ModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT,
                'condition' => false,
                'class' => \PoP\GraphQLClientsForWP\Component::class,
                'envVariable' => \PoP\GraphQLClientsForWP\Environment::GRAPHIQL_CLIENT_ENDPOINT,
                'value' => '',
            ],
            [
                'module' => ModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT,
                'condition' => false,
                'class' => \PoP\GraphQLClientsForWP\Component::class,
                'envVariable' => \PoP\GraphQLClientsForWP\Environment::VOYAGER_CLIENT_ENDPOINT,
                'value' => '',
            ],
        ];
        foreach ($moduleToComponentClassConfigurationMappings as $mapping) {
            // Copy value if either the condition is not set, or if it equals the enabled/disabled module state
            $condition = $mapping['condition'];
            if (is_null($condition) || $moduleRegistry->isModuleEnabled($mapping['module']) === $condition) {
                $componentClassConfiguration[$mapping['class']][$mapping['envVariable']] = $mapping['value'];
            }
        }
    }

    /**
     * Provide the classes of the components whose schema initialization must be skipped
     *
     * @return array
     */
    public static function getSkippingSchemaComponentClasses(): array
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();

        // Component classes enabled/disabled by module
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
        return GeneralUtils::arrayFlatten(array_values(
            $skipSchemaModuleComponentClasses
        ));
    }
}
