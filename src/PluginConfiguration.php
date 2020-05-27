<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Environment;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolver;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\APIEndpointsForWP\Environment as APIEndpointsForWPEnvironment;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\APIEndpointsForWP\ComponentConfiguration as APIEndpointsForWPComponentConfiguration;

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
        self::defineEnvironmentConstantsFromModules();
    }

    /**
     * Define the values for certain environment constants depending on a module being enabled or not
     *
     * @return array
     */
    protected static function defineEnvironmentConstantsFromModules(): void
    {
        // All the environment variables to override
        $mappings = [
            [
                'module' => ModuleResolver::SINGLE_ENDPOINT,
                'class' => APIEndpointsForWPComponentConfiguration::class,
                'envVariable' => APIEndpointsForWPEnvironment::GRAPHQL_API_ENDPOINT,
                'condition' => false,
                'value' => '',
            ],
        ];
        // For each environment variable, see if its value has been saved in the settings
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        foreach ($mappings as $mapping) {
            $hookName = ComponentConfigurationHelpers::getHookName($mapping['class'], $mapping['envVariable']);
            $module = $mapping['module'];
            $condition = $mapping['condition'];
            $valueIfCondition = $mapping['value'];
            \add_filter(
                $hookName,
                function ($value) use ($moduleRegistry, $module, $condition, $valueIfCondition) {
                    if ($moduleRegistry->isModuleEnabled($module) === $condition) {
                        return $valueIfCondition;
                    }
                    return $value;
                },
                PHP_INT_MAX, // Execute last, to override any other filter
                1
            );
        }
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
                'optionName' => 'usePrivateSchemaMode',
                'defaultValue' => false,
            ],
            [
                'class' => AccessControlComponentConfiguration::class,
                'envVariable' => AccessControlEnvironment::ENABLE_INDIVIDUAL_CONTROL_FOR_PUBLIC_PRIVATE_SCHEMA_MODE,
                'optionName' => 'enableIndividualControlForPublicPrivateSchemaMode',
                'defaultValue' => true,
            ],
        ];
        // For each environment variable, see if its value has been saved in the settings
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        foreach ($mappings as $mapping) {
            $hookName = ComponentConfigurationHelpers::getHookName($mapping['class'], $mapping['envVariable']);
            $optionName = $mapping['optionName'];
            $defaultValue = $mapping['defaultValue'];
            \add_filter(
                $hookName,
                function () use ($userSettingsManager, $optionName, $defaultValue) {
                    if ($userSettingsManager->hasSetting($optionName)) {
                        return $userSettingsManager->getSetting($optionName);
                    }
                    return $defaultValue;
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
}
