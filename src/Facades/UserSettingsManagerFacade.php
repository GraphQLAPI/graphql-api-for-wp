<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManager;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;

// use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Obtain an instance of hte UserSettingsManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class UserSettingsManagerFacade
{
    private static $instance;
    public static function getInstance(): UserSettingsManagerInterface
    {
        if (is_null(self::$instance)) {
            self::$instance = new UserSettingsManager();
        }
        return self::$instance;
    }
    // public static function getInstance(): UserSettingsManagerInterface
    // {
    //     return ContainerBuilderFactory::getInstance()->get('user_settings_manager');
    // }
}
