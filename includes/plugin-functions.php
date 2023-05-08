<?php

/**
 * Register the "book" custom post type
 */
function pluginprefix_setup_post_type()
{
    register_post_type('book', ['public' => true]);
}
add_action('init', 'pluginprefix_setup_post_type');

?>

