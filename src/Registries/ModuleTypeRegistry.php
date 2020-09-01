<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolverInterface;

class ModuleTypeRegistry implements ModuleTypeRegistryInterface
{
    protected array $moduleTypeResolvers = [];

    public function addModuleTypeResolver(ModuleTypeResolverInterface $moduleTypeResolver): void
    {
        foreach ($moduleTypeResolver::getModuleTypesToResolve() as $moduleType) {
            $this->moduleTypeResolvers[$moduleType] = $moduleTypeResolver;
        }
    }

    public function getModuleTypeResolver(string $module): ?ModuleTypeResolverInterface
    {
        return $this->moduleTypeResolvers[$module];
    }
}
