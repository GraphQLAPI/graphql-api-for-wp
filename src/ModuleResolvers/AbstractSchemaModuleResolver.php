<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

abstract class AbstractSchemaModuleResolver extends AbstractModuleResolver
{
    public const MODULE_TYPE_SCHEMA = 'schema';

    /**
     * The type of the module
     *
     * @param string $module
     * @return string
     */
    public function getModuleType(string $module): string
    {
        return self::MODULE_TYPE_SCHEMA;
    }
}
