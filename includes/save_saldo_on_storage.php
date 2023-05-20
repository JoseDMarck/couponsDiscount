<?php

add_action('woocommerce_thankyou', 'saveSaldoOnLocalStorage');
function saveSaldoOnLocalStorage()
{

    echo "saveSaldoOnLocalStorage";
    // wp_enqueue_script('coupon_discount_save_saldo', plugins_url('/public/js/gd__save_saldo.js', TC__FILE__), array('jquery'), '20200110');

    // wp_localize_script(
    //     'coupon_discount_save_saldo',
    //     'wp_object',
    //     array(
    //         'ajax_url' => admin_url('admin-ajax.php'),
    //         'newSaldo' => $saldo,
    //         'userdata' => $user_data
    //     )
    // );
    // wp_enqueue_script('coupon_discount_save_saldo');
}
