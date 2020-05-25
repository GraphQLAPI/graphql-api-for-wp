<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;

// use GraphQLAPI\GraphQLAPI\Container\ContainerBuilderUtils;

/**
 * Initialize component
 */
class PluginComponent extends AbstractComponent
{
    use YAMLServicesTrait;

    // const VERSION = '0.1.0';

    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\Root\Component::class,
        ];
    }

    /**
     * Initialize services
     */
    protected static function doInitialize(): void
    {
        parent::doInitialize();
        self::initYAMLServices(dirname(__DIR__), '', 'plugin-services.yaml');
    }

    // /**
    //  * Boot component
    //  *
    //  * @return void
    //  */
    // public static function beforeBoot(): void
    // {
    //     parent::beforeBoot();

    //     // Initialize classes
    //     ContainerBuilderUtils::registerModuleResolversFromNamespace(__NAMESPACE__ . '\\ModuleResolvers');
    // }
}
