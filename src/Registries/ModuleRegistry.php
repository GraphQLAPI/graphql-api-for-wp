<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverInterface;

class ModuleRegistry implements ModuleRegistryInterface
{
    protected $moduleResolvers = [];
    public function addModuleResolver(ModuleResolverInterface $moduleResolver): void
    {
        foreach ($moduleResolver::getModulesToResolve() as $module) {
            $this->moduleResolvers[$module] = $moduleResolver;
        }
    }
    public function getAllModules(
        bool $onlyEnabled = false,
        bool $onlyHasSettings = false,
        bool $onlyVisible = true
    ): array {
        $modules = array_keys($this->moduleResolvers);
        if ($onlyEnabled) {
            $modules = array_filter(
                $modules,
                function ($module) {
                    return $this->isModuleEnabled($module);
                }
            );
        }
        if ($onlyHasSettings) {
            $modules = array_filter(
                $modules,
                function ($module) {
                    return $this->getModuleResolver($module)->hasSettings($module);
                }
            );
        }
        if ($onlyVisible) {
            $modules = array_filter(
                $modules,
                function ($module) {
                    return !$this->getModuleResolver($module)->isHidden($module);
                }
            );
        }
        return $modules;
    }
    public function getModuleResolver(string $module): ?ModuleResolverInterface
    {
        return $this->moduleResolvers[$module];
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
        // If the user can't disable it, then it must be enabled
        if (!$moduleResolver->canBeDisabled($module)) {
            return true;
        }
        $moduleID = $moduleResolver->getID($module);
        // Check if the value has been saved on the DB
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if ($userSettingsManager->hasSetModuleEnabled($moduleID)) {
            return $userSettingsManager->isModuleEnabled($moduleID);
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
                    // Check if it has the "inverse" token at the beginning,
                    // then it depends on the module being disabled, not enabled
                    if (substr($dependedModule, 0, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY)) == ModuleRegistryTokens::INVERSE_DEPENDENCY) {
                        // The module is everything after the token
                        $dependedModule = substr($dependedModule, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY));
                        return !$this->isModuleEnabled($dependedModule);
                    }
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

    /**
     * Used to indicate that the dependency on the module is on its being disabled, not enabled
     *
     * @param string $dependedModule
     * @return string
     */
    public function getInverseDependency(string $dependedModule): string
    {
        // Check if it already has the "inverse" token at the beginning,
        // then take it back to normal
        if ($this->isInverseDependency($dependedModule)) {
            // The module is everything after the token "!"
            return substr($dependedModule, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY));
        }
        // Add "!" before the module
        return ModuleRegistryTokens::INVERSE_DEPENDENCY . $dependedModule;
    }
    /**
     * Indicate if the dependency is on its being disabled, not enabled
     */
    public function isInverseDependency(string $dependedModule): bool
    {
        return substr($dependedModule, 0, strlen(ModuleRegistryTokens::INVERSE_DEPENDENCY)) == ModuleRegistryTokens::INVERSE_DEPENDENCY;
    }
}
