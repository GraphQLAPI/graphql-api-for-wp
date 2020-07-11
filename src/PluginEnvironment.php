<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

class PluginEnvironment
{
    public const PLUGIN_ENVIRONMENT = 'PLUGIN_ENVIRONMENT';

    /**
     * Return a value for a variable, checking if it is defined in the environment
     * first, and in the wp-config.php second
     *
     * @return mixed
     */
    protected static function getValueFromEnvironmentOrWPConfig(string $envVariable)
    {
        if (isset($_ENV[$envVariable])) {
            return $_ENV[$envVariable];
        }

        if (PluginConfiguration::isWPConfigConstantDefined($envVariable)) {
            return PluginConfiguration::getWPConfigConstantValue($envVariable);
        };

        return null;
    }

    /**
     * The label to show when the value is empty
     *
     * @return boolean
     */
    public static function getPluginEnvironment(): string
    {
        $environments = [
            'production',
            'development'
        ];
        $value = self::getValueFromEnvironmentOrWPConfig(self::PLUGIN_ENVIRONMENT);
        if (!is_null($value) && in_array($value, $environments)) {
            return $value;
        }
        // Default value
        return 'production';
    }
}
