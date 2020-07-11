<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\PluginEnvironment;

/**
 * The cache modules have different behavior depending on the environment:
 * - "development": visible, disabled by default
 * - "production": hidden, enabled by default
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
abstract class AbstractCacheFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    public function isHidden(string $module): bool
    {
        $environment = PluginEnvironment::getPluginEnvironment();
        return $environment == PluginEnvironment::PLUGIN_ENVIRONMENT_PROD;
    }

    public function isEnabledByDefault(string $module): bool
    {
        $environment = PluginEnvironment::getPluginEnvironment();
        return $environment == PluginEnvironment::PLUGIN_ENVIRONMENT_PROD;
    }
}
