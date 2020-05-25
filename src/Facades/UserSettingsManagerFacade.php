<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

/**
 * Obtain an instance of the UserSettingsManager.
 */
class UserSettingsManagerFacade
{
    public static function getInstance(): UserSettingsManagerInterface
    {
        return ContainerBuilderFactory::getInstance()->get('user_settings_manager');
    }
}
