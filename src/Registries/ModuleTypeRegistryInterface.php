<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolverInterface;

interface ModuleTypeRegistryInterface
{
    public function addModuleTypeResolver(ModuleTypeResolverInterface $moduleTypeResolver): void;
    public function getModuleTypeResolver(string $moduleType): ?ModuleTypeResolverInterface;
}
