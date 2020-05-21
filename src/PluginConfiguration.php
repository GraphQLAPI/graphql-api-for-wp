<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Environment;
use GraphQLAPI\GraphQLAPI\Settings\Settings;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\AccessControl\Environment as AccessControlEnvironment;

class PluginConfiguration
{
    /**
     * Initialize all configuration
     *
     * @return array
     */
    public static function initialize(): void
    {
        self::setPredefinedEnvVariables();
        self::mapEnvVariablesToWPConfigConstants();
        self::defineEnvironmentConstantsFromSettings();
    }

    /**
     * Define the pre-determined values for certain environment constants
     *
     * @return array
     */
    protected static function setPredefinedEnvVariables(): void
    {
        /**
         * Do not add caching
         */
        $_ENV[\PoP\Engine\Environment::ADD_MANDATORY_CACHE_CONTROL_DIRECTIVE] = "false";
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
        $settings = \get_option(Settings::OPTIONS_NAME);
        foreach ($mappings as $mapping) {
            $hookName = ComponentConfigurationHelpers::getHookName($mapping['class'], $mapping['envVariable']);
            $optionName = $mapping['optionName'];
            $defaultValue = $mapping['defaultValue'];
            \add_filter(
                $hookName,
                function () use ($settings, $optionName, $defaultValue) {
                    if (isset($settings[$optionName])) {
                        return $settings[$optionName];
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
