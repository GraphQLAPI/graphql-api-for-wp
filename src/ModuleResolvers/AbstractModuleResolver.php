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
}
