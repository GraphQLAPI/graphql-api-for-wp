<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Settings;

/**
 * Settings
 */
class Settings
{
    public const OPTIONS_NAME = 'graphql-api-settings';

    public static function getDefaultSchemaConfiguration(): ?int
    {
        return null;
    }
}
