<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Config;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Users\TypeResolvers\UserTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\UserRolesAccessControl\Services\AccessControlGroups as UserRolesAccessControlGroups;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    /**
     * Validate that only the right users can access private fields
     *
     * @return void
     */
    protected static function configure()
    {
        if ($capabilities = ['manage_options']) {
            ContainerBuilderUtils::injectValuesIntoService(
                'access_control_manager',
                'addEntriesForFields',
                UserRolesAccessControlGroups::CAPABILITIES,
                [
                    [RootTypeResolver::class, 'accessControlLists', $capabilities],
                    [RootTypeResolver::class, 'cacheControlLists', $capabilities],
                ]
            );
        }
    }
}
