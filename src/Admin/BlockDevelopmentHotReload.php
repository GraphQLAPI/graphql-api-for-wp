<?php
namespace Leoloso\GraphQLByPoPWPPlugin\Admin;

class BlockDevelopmentHotReload {

    public function init(): void
    {
        // Initialize the GraphiQL
        \add_action('init', [$this, 'maybeRegisterScript']);
    }

    public function maybeRegisterScript(): void
    {
        // Enable Hot Reloading! Only for DEV
        // By either constant definition, or environment variable
        if (
            \is_admin() &&
            (
                (defined('ENABLE_HOT_RELOADING_FOR_DEV') && ENABLE_HOT_RELOADING_FOR_DEV) ||
                (isset($_ENV['ENABLE_HOT_RELOADING_FOR_DEV']) && $_ENV['ENABLE_HOT_RELOADING_FOR_DEV'])
            )
        ) {
            \wp_register_script(
                'livereload',
                'http://localhost:35729/livereload.js'
            );
            \wp_enqueue_script(
                'livereload'
            );
        }
	}
}
