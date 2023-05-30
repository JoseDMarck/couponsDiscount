<?php
add_action('admin_menu', 'trendee_coupons_menu');

function trendee_coupons_menu()
{

    add_menu_page(
        __('Trendee Coupons', 'textdomain'),
        'Trendee Coupons',
        'manage_options',
        "coupons-discount/admin/includes/discount-info.php",
        '',
        plugin_dir_url(TC__FILE__) . "public/images/icon.png",
        6
    );
}
