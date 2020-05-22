<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ModuleRegistryFacade
{
    public static function getInstance(): ModuleRegistryInterface
    {
        return ContainerBuilderFactory::getInstance()->get('module_registry');
    }
}
