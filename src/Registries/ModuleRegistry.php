<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;

class ModuleRegistry implements ModuleRegistryInterface
{
    protected $moduleResolverClasses = [];
    public function addModuleResolverClass(string $moduleResolverClass): void
    {
        foreach ($moduleResolverClass::getModulesToResolve() as $module) {
            $this->moduleResolverClasses[$module] = $moduleResolverClass;
        }
    }
    // public function getModuleResolverClasses(): array
    // {
    //     return $this->moduleResolverClasses;
    // }
    public function getAllModules(bool $onlyVisible = true): array
    {
        $modules = array_keys($this->moduleResolverClasses);
        if ($onlyVisible) {
            return array_filter(
                $modules,
                function ($module) {
                    return !$this->getModuleResolver($module)->isHidden($module);
                }
            );
        }
        return $modules;
    }
    public function getModuleResolverClass(string $module): ?string
    {
        return $this->moduleResolverClasses[$module];
    }
    public function getModuleResolver(string $module): ?ModuleResolverInterface
    {
        if ($moduleResolverClass = $this->getModuleResolverClass($module)) {
            $instanceManager = InstanceManagerFacade::getInstance();
            return $instanceManager->getInstance($moduleResolverClass);
        }
        return null;
    }
    public function getModuleID(string $module): string
    {
        return $module;
    }
    public function isModuleEnabled(string $module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);
        if (is_null($moduleResolver)) {
            return false;
        }
        // Check that all requirements are satisfied
        if (!$moduleResolver->areRequirementsSatisfied($module)) {
            return false;
        }
        // Check that all depended-upon modules are enabled
        $dependedModuleLists = $moduleResolver->getDependedModuleLists($module);
        /**
         * This is a list of lists of modules, as to model both OR and AND conditions
         * The innermost list is an OR: if any module is enabled, then the condition succeeds
         * The outermost list is an AND: all list must succeed for this module to be enabled
         * Eg: the Schema Configuration is enabled if either the Custom Endpoints or
         * the Persisted Query are enabled:
         * [
         *   [self::PERSISTED_QUERIES, self::CUSTOM_ENDPOINTS],
         * ]
         */
        foreach ($dependedModuleLists as $dependedModuleList) {
            if (!$dependedModuleList) {
                continue;
            }
            $dependedModuleListEnabled = array_map(
                [$this, 'isModuleEnabled'],
                $dependedModuleList
            );
            if (!in_array(true, $dependedModuleListEnabled)) {
                return false;
            }
        }
        $moduleID = $this->getModuleID($module);
        // Check if the value has been saved on the DB
        if (false) {
            return false;
        }
        // Get the default value from the resolver
        return $moduleResolver->isEnabledByDefault($module);
    }
}
