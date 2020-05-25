<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Settings;

use GraphQLAPI\GraphQLAPI\Settings\Options;

class UserSettingsManager implements UserSettingsManagerInterface
{
    /**
     * Cache the values in memory
     *
     * @var array
     */
    protected $options = [];

    public function hasSettingsItem(string $optionName): bool
    {
        return $this->hasItem(Options::SETTINGS, $optionName);
    }
    public function getSettingsItem(string $optionName)
    {
        return $this->getItem(Options::SETTINGS, $optionName);
    }

    public function hasModuleItem(string $optionName): bool
    {
        return $this->hasItem(Options::MODULES, $optionName);
    }
    public function getModuleItem(string $optionName)
    {
        return $this->getItem(Options::MODULES, $optionName);
    }

    /**
     * Get the stored value for the option under the group
     *
     * @param array|null $var
     * @param string $group
     * @param string $optionName
     * @return void
     */
    protected function getItem(string $group, string $optionName)
    {
        $this->maybeLoadOptions($group);
        return $this->options[$group][$optionName];
    }

    /**
     * Is there a stored value for the option under the group
     *
     * @param string $group
     * @param string $optionName
     * @return void
     */
    protected function hasItem(string $group, string $optionName): bool
    {
        $this->maybeLoadOptions($group);
        return isset($this->options[$group][$optionName]);
    }

    /**
     * Load the options from the DB
     *
     * @param string $group
     * @return void
     */
    protected function maybeLoadOptions(string $group): void
    {
        // Lazy load the options
        if (is_null($this->options[$group])) {
            $this->options[$group] = \get_option($group);
        }
    }
}
