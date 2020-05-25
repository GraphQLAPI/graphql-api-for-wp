<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
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
        if (!$this->areDependedModulesEnabled($module)) {
            return false;
        }
        $moduleID = $this->getModuleID($module);
        // Check if the value has been saved on the DB
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if ($userSettingsManager->hasModuleItem($moduleID)) {
            return $userSettingsManager->getModuleItem($moduleID);
        }
        // Get the default value from the resolver
        return $moduleResolver->isEnabledByDefault($module);
    }

    /**
     * Indicate if a module's depended-upon modules are all enabled
     *
     * @param string $module
     * @return boolean
     */
    protected function areDependedModulesEnabled(string $module): bool
    {
        $moduleResolver = $this->getModuleResolver($module);
        if (is_null($moduleResolver)) {
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
                function ($dependedModule) {
                    return $this->isModuleEnabled($dependedModule);
                },
                $dependedModuleList
            );
            if (!in_array(true, $dependedModuleListEnabled)) {
                return false;
            }
        }
        return true;
    }

    /**
     * If a module was disabled by the user, then the user can enable it.
     * If it is disabled because its requirements are not satisfied,
     * or its dependencies themselves disabled, then it cannot be enabled by the user.
     *
     * @param string $module
     * @return boolean
     */
    public function canModuleBeEnabled(string $module): bool
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
        if (!$this->areDependedModulesEnabled($module)) {
            return false;
        }
        return true;
    }
}
