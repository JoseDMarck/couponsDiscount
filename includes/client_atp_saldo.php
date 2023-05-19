<?php

add_action('init', 'getSaldoFromLocalStorage');
function getSaldoFromLocalStorage()
{

    wp_enqueue_script('coupon_discount', plugins_url('/public/js/scripts.js', TC__FILE__), array('jquery'), '20200110');
    wp_localize_script(
        'coupon_discount',
        'wp_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
        )
    );
    wp_enqueue_script('coupon_discount');
}

/*----------------------------------------------------------------
/*  Guardamos el valor del ATP_saldo en la base de datos 
/*----------------------------------------------------------------*/

add_action('wp_ajax_set_atp_saldo', 'set_atp_saldo', 9);
function set_atp_saldo()
{
    if (isset($_REQUEST)) {
        TrendeeCoupons::setATPSaldo($_REQUEST['saldo']);
    }
    wp_die();
}
