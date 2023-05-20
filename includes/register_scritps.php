<?php

add_action('init', 'wpdocs_enqueue_custom_admin_style');
function wpdocs_enqueue_custom_admin_style()
{
    wp_register_style('custom_wp_admin_css', plugin_dir_url(TC__FILE__) . 'public/css/gd__modal_coupon.css', false, '1.0.0');
    wp_enqueue_style('custom_wp_admin_css');
}

add_action('init', 'wpdocs_enqueue_magic_library');
function wpdocs_enqueue_magic_library()
{
    wp_register_style('magic_library_css', plugin_dir_url(TC__FILE__) . 'public/css/magic.min.css', false, '1.0.0');
    wp_enqueue_style('magic_library_css');
}
