<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Environment;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;

class PluginConfiguration
{
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
     * Define the values for certain environment constants from the plugin settings
     *
     * @return array
     */
    protected static function defineEnvironmentConstantsFromSettings(): void
    {
        // All the environment variables to override
        $mappings = [
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
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE,
                'module' => ModuleResolver::PUBLIC_PRIVATE_SCHEMA,
                'option' => ModuleResolver::OPTION_ENABLE_GRANULAR,
            ],
        ];
        // For each environment variable, see if its value has been saved in the settings
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        foreach ($mappings as $mapping) {
            $hookName = ComponentConfigurationHelpers::getHookName($mapping['class'], $mapping['envVariable']);
            $module = $mapping['module'];
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
                },
                10,
                3
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
            ComponentConfiguration::class => Environment::ADD_EXCERPT_AS_DESCRIPTION,
        ];
        // For each environment variable, see if it has been defined as a wp-config.php constant
        foreach ($mappings as $mappingClass => $mappingEnvVariable) {
            $hookName = ComponentConfigurationHelpers::getHookName($mappingClass, $mappingEnvVariable);
            \add_filter(
                $hookName,
                [self::class, 'useWPConfigConstant'],
                10,
                3
            );
        }
    }

    /**
     * Constants defined in wp-config.php must start with this prefix to override GraphQL API environment variables
     *
     * @return string
     */
    public static function getWPConfigConstantPrefix(): string
    {
        return 'GRAPHQL_API_';
    }

    /**
     * Override the value of an environment variable if it has been definedas a constant
     * in wp-config.php, with the environment name prepended with "GRAPHQL_API_"
     *
     * @param [type] $value
     * @param [type] $class
     * @param [type] $envVariable
     * @return mixed
     */
    public static function useWPConfigConstant($value, $class, $envVariable)
    {
        $constantName = self::getWPConfigConstantPrefix() . $envVariable;
        if (defined($constantName)) {
            return constant($constantName);
        }
        return $value;
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
