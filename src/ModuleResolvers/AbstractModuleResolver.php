<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;

abstract class AbstractModuleResolver implements ModuleResolverInterface
{
    public function getDependedModuleLists(string $module): array
    {
        return [];
    }

    public function areRequirementsSatisfied(string $module): bool
    {
        return true;
    }

    public function isHidden(string $module): bool
    {
        return false;
    }

    public function getID(string $module): string
    {
        return $module;
    }

    public function getDescription(string $module): string
    {
        return '';
    }

    public function hasSettings(string $module): bool
    {
        return false;
    }

    public function isEnabledByDefault(string $module): bool
    {
        return true;
    }
}
