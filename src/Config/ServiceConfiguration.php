<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Config;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use Leoloso\GraphQLByPoPWPPlugin\Security\UserAuthorization;
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
        $schemaEditorAccessCapability = UserAuthorization::getSchemaEditorAccessCapability();
        $capabilities = [$schemaEditorAccessCapability];
        ContainerBuilderUtils::injectValuesIntoService(
            'access_control_manager',
            'addEntriesForFields',
            UserRolesAccessControlGroups::CAPABILITIES,
            [
                [RootTypeResolver::class, 'accessControlLists', $capabilities],
                [RootTypeResolver::class, 'cacheControlLists', $capabilities],
                [RootTypeResolver::class, 'fieldDeprecationLists', $capabilities],
            ]
        );
    }
}
