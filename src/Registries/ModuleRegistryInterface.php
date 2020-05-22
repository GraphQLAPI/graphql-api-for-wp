<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;

interface ModuleRegistryInterface
{
    public function addModuleResolverClass(string $moduleResolverClass): void;
    // public function getModuleResolverClasses(): array;
    public function getAllModules(bool $onlyVisible = true): array;
    public function getModuleResolverClass(string $module): ?string;
    public function getModuleResolver(string $module): ?ModuleResolverInterface;
    public function getModuleID(string $module): string;
    public function isModuleEnabled(string $module): bool;
}
