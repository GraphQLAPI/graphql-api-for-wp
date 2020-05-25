<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

interface UserSettingsManagerInterface
{
    public function hasSettingsItem(string $optionName): bool;
    public function getSettingsItem(string $optionName);
    public function hasModuleItem(string $optionName): bool;
    public function getModuleItem(string $optionName);
}
