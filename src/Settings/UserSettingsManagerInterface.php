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
    public function getSetting(string $module, string $option);
    public function hasSetModuleEnabled(string $moduleID): bool;
    public function isModuleEnabled(string $moduleID): bool;
    public function setModuleEnabled(string $moduleID, bool $isEnabled): void;
    public function setModulesEnabled(array $moduleIDValues): void;
}
