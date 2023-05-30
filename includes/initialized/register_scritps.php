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

add_action('init', 'wpdocs_enqueue_page_admin_style');
function wpdocs_enqueue_page_admin_style()
{
    wp_register_style('custom_page_admin_css', plugin_dir_url(TC__FILE__) . 'admin/css/gd__page_admin.css', false, '1.0.0');
    wp_enqueue_style('custom_page_admin_css');
}

add_action('admin_init', 'wpdocs_enqueue_page_admin_js', 10);
function wpdocs_enqueue_page_admin_js()
{
    wp_enqueue_script('gd__page_admin', plugins_url('/admin/js/gd__page_admin.js', TC__FILE__), array(), '001');
    wp_localize_script(
        'gd__page_admin',
        'wp_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
        )
    );
}
