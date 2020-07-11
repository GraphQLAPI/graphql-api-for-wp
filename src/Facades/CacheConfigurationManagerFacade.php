<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Cache\CacheConfigurationManager;
use GraphQLAPI\GraphQLAPI\Cache\CacheConfigurationManagerInterface;

// use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Obtain an instance of the CacheConfigurationManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class CacheConfigurationManagerFacade
{
    private static $instance;

    public static function getInstance(): CacheConfigurationManagerInterface
    {
        if (is_null(self::$instance)) {
            self::$instance = new CacheConfigurationManager();
        }
        return self::$instance;
    }

    // public static function getInstance(): CacheConfigurationManagerInterface
    // {
    //     return ContainerBuilderFactory::getInstance()->get('user_settings_manager');
    // }
}
