<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

interface UserSettingsManagerInterface
{
    public function hasSettingsItem(string $item): bool;
    public function getSettingsItem(string $item);
    public function hasModuleItem(string $module): bool;
    public function getModuleItem(string $module);
    public function storeModuleItem(string $module, $value): void;
    public function storeModuleItems(array $moduleValues): void;
}
