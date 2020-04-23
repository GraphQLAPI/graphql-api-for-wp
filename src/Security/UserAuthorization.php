<?php

declare(strict_types=1);

namespace Leoloso\GraphQLByPoPWPPlugin\Security;

/**
 * UserAuthorization
 */
class UserAuthorization
{
    public static function canAccessConfigurationContent(): bool
    {
        return \is_user_logged_in() && \current_user_can('manage_options');
    }
}
