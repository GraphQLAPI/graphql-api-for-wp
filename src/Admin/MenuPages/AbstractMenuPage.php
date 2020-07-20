<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Admin\MenuPages;

use GraphQLAPI\GraphQLAPI\Admin\MenuPages\MenuPageInterface;

/**
 * Menu page
 */
abstract class AbstractMenuPage implements MenuPageInterface
{
    protected $hookName;

    public function setHookName(string $hookName): void
    {
        $this->hookName = $hookName;
    }

    public function getHookName(): ?string
    {
        return $this->hookName;
    }

    /**
     * Initialize menu page. Function to override
     *
     * @return void
     */
    public function initialize(): void
    {
        \add_action(
            'admin_enqueue_scripts',
            [$this, 'maybeEnqueueAssets']
        );
    }

    /**
     * Maybe enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    public function maybeEnqueueAssets(): void
    {
        // Enqueue assets if we are on that page
        if ($this->isCurrentScreen()) {
            $this->enqueueAssets();
        }
    }

    abstract public function getMenuName(): string;
    abstract public function getMenuPageSlug(): string;

    protected function isCurrentScreen(): bool
    {
        $screenID = $this->getScreenID();
        // Check we are on the specific screen
        $currentScreen = \get_current_screen()->id;
        // If it is the top level page, the current screen is prepended with "toplevel_page_"
        // If not, the current screen is prepended with the section name
        // Then, check that the screen ends with the requested screen ID
        return substr($currentScreen, -1 * strlen($screenID)) == $screenID;
    }

    public function getScreenID(): string
    {
        return sprintf(
            '%s_%s',
            $this->getMenuName(),
            $this->getMenuPageSlug()
        );
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    protected function enqueueAssets(): void
    {
    }
}
