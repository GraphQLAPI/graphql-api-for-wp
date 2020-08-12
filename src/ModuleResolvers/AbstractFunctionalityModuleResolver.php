<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

abstract class AbstractFunctionalityModuleResolver extends AbstractModuleResolver
{
    public const MODULE_TYPE_FUNCTIONALITY = 'functionality';

    /**
     * The type of the module
     */
    public function getModuleType(string $module): string
    {
        return self::MODULE_TYPE_FUNCTIONALITY;
    }
}
