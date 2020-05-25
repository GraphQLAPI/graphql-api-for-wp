<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;

interface ModuleRegistryInterface
{
    public function addModuleResolver(ModuleResolverInterface $moduleResolver): void;
    public function getAllModules(bool $onlyVisible = true): array;
    public function getModuleResolver(string $module): ?ModuleResolverInterface;
    public function isModuleEnabled(string $module): bool;
    /**
     * If a module was disabled by the user, then the user can enable it.
     * If it is disabled because its requirements are not satisfied,
     * or its dependencies themselves disabled, then it cannot be enabled by the user.
     *
     * @param string $module
     * @return boolean
     */
    public function canModuleBeEnabled(string $module): bool;
}
