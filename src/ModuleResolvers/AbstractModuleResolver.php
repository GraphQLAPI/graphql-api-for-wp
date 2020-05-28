<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
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
        $moduleID = strtolower($module);
        // $moduleID = strtolower(str_replace(
        //     ['/', ' '],
        //     '-',
        //     $moduleID
        // ));
        /**
         * Replace all the "\" from the namespace with "."
         * Otherwise there is problem when encoding/decoding,
         * since "\" is encoded as "\\"
         */
        return str_replace(
            '\\', //['\\', '/', ' '],
            '.',
            $moduleID
        );
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

    /**
     * By default, point to https://graphql-api.com/modules/{component-slug}
     *
     * @param string $module
     * @return string|null
     */
    public function getURL(string $module): ?string
    {
        $moduleSlug = $this->getSlug($module);
        $moduleURLBase = $this->getURLBase($module);
        return \trailingslashit($moduleURLBase) . $moduleSlug . '/';
    }

    /**
     * By default, the slug is the module's name
     *
     * @param string $module
     * @return string
     */
    protected function getSlug(string $module): string
    {
        // The module's name without the owner/package
        $pos = strrpos($module, '\\');
        if ($pos !== false) {
            return substr($module, $pos + strlen('\\'));
        }
        return $module;
    }

    /**
     * Return the default URL base for the module, defined through configuration
     *
     * @param string $module
     * @return string
     */
    protected function getURLBase(string $module): string
    {
        return ComponentConfiguration::getModuleURLBase();
    }
}
