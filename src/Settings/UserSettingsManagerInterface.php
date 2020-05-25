<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

interface UserSettingsManagerInterface
{
    public function hasSetting(string $item): bool;
    /**
     * No return type because it could be a bool/int/string
     *
     * @param string $item
     * @return mixed
     */
    public function getSetting(string $item);
    public function hasSetModuleEnabled(string $module): bool;
    public function isModuleEnabled(string $module): bool;
    public function setModuleEnabled(string $module, bool $isEnabled): void;
    public function setModulesEnabled(array $moduleValues): void;
}
