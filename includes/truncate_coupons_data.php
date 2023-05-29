<?php

add_action('wp_ajax_truncate_coupons_data', 'truncate_coupons_data', 10);
function truncate_coupons_data()
{
    global $wpdb;
    $wpdb->query('TRUNCATE TABLE wp_coupons_data');
    wp_die();
}
