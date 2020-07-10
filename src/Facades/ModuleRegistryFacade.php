<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

// use PoP\Root\Container\ContainerBuilderFactory;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\FunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistry;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;

/**
 * Obtain an instance of the ModuleRegistry.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class ModuleRegistryFacade
{
    private static $instance;
    public static function getInstance(): ModuleRegistryInterface
    {
        if (is_null(self::$instance)) {
            // Instantiate
            self::$instance = new ModuleRegistry();
            // Add the ModuleResolvers
            self::$instance->addModuleResolver(new FunctionalityModuleResolver());
            self::$instance->addModuleResolver(new SchemaModuleResolver());
        }
        return self::$instance;
    }
    // public static function getInstance(): ModuleRegistryInterface
    // {
    //     return ContainerBuilderFactory::getInstance()->get('module_registry');
    // }
}
