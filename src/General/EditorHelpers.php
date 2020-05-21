<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\General;

class EditorHelpers
{
    /**
     * Get the post type currently being created/edited in the editor
     *
     * @return string
     */
    public static function getEditingPostType(): ?string
    {
        // When in the editor, there is no direct way to obtain the post type in hook "init",
        // since $typenow has not been initialized yet
        // Hence, recreate the logic to get post type from URL if we are on post-new.php, or
        // from edited post in post.php
        if (!\is_admin()) {
            return null;
        }
        global $pagenow;
        if (!in_array($pagenow, ['post-new.php', 'post.php'])) {
            return null;
        }
        if ('post-new.php' === $pagenow) {
            if (isset($_REQUEST['post_type']) && \post_type_exists($_REQUEST['post_type'])) {
                $typenow = $_REQUEST['post_type'];
            };
        } elseif ('post.php' === $pagenow) {
            if (isset($_GET['post']) && isset($_POST['post_ID']) && (int) $_GET['post'] !== (int) $_POST['post_ID']) {
                // Do nothing
            } elseif (isset($_GET['post'])) {
                $post_id = (int) $_GET['post'];
            } elseif (isset($_POST['post_ID'])) {
                $post_id = (int) $_POST['post_ID'];
            }
            if ($post_id) {
                $post = \get_post($post_id);
                $typenow = $post->post_type;
            }
        }
        return $typenow;
    }
}
