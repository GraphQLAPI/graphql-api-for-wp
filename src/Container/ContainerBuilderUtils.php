<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container;

use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils as ComponentModelContainerBuilderUtils;

class ContainerBuilderUtils extends ComponentModelContainerBuilderUtils
{

    /**
     * Register all modules located under the specified namespace
     *
     * @param string $namespace
     * @return void
     */
    public static function registerModuleResolversFromNamespace(string $namespace, bool $includeSubfolders = true): void
    {
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        foreach (self::getServiceClassesUnderNamespace($namespace, $includeSubfolders) as $serviceClass) {
            $moduleRegistry->addModuleResolverClass($serviceClass);
        }
    }
}
