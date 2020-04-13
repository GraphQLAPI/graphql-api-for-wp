<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

use Leoloso\GraphQLByPoPWPPlugin\Admin\MenuPageInterface;

/**
 * Menu page
 */
abstract class AbstractMenuPage implements MenuPageInterface
{
    public function __construct()
    {
        $this->init();
        \add_action(
            'admin_enqueue_scripts',
            [$this, 'maybeEnqueueAssets']
        );
    }

    /**
     * Initialize menu page. Function to override
     *
     * @return void
     */
    protected function init(): void
    {
    }

    /**
     * Maybe enqueue the required assets and initialize the localized scripts
     *
     * @return void
     */
    public function maybeEnqueueAssets(): void
    {
        // Enqueue if either it doesn't specify a screen ID, or it does and we are on that page
        $enqueueAssets = false;
        $screenID = $this->getScreenID();
        if ($screenID) {
            // Check we are on the specific screen
            $currentScreen = \get_current_screen()->id;
            // If it is the top level page, the current screen is prepended with "toplevel_page_"
            // If not, the current screen is prepended with the section name
            // Then, check that the screen ends with the requested screen ID
            $enqueueAssets = substr($currentScreen, -1 * strlen($screenID)) == $screenID;
        } else {
            $enqueueAssets = true;
        }
        if ($enqueueAssets) {
            $this->enqueueAssets();
        }
    }

    protected function getScreenID(): ?string
    {
        return null;
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
