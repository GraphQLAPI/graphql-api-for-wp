<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

interface ModuleResolverInterface
{
    public static function getModulesToResolve(): array;
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
    public function getDependedModuleLists(string $module): array;
    /**
     * Indicates if a module has all requirements satisfied (such as version of WordPress) to be enabled
     *
     * @param string $module
     * @return boolean
     */
    public function areRequirementsSatisfied(string $module): bool;
    public function isHidden(string $module): bool;
    public function getID(string $module): string;
    public function getName(string $module): string;
    public function getDescription(string $module): string;
    public function hasSettings(string $module): bool;
    public function isEnabledByDefault(string $module): bool;
    public function getURL(string $module): ?string;
}
