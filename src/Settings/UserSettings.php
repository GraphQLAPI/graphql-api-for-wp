<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

/**
 * User Settings
 */
class UserSettings
{
    public const OPTION_SETTINGS = 'graphql-api-settings';

    public static function getDefaultSchemaConfiguration(): ?int
    {
        return null;
    }
}
