<?php

add_action('init', 'getSaldoFromLocalStorage');
function getSaldoFromLocalStorage()
{

    if (!is_user_logged_in()) {
        return false;
    }

    //TODO: programar para que no haga la peticion cada vez que se refresque una página...

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

add_action('wp_ajax_set_atp_saldo', 'set_atp_saldo', 10);
function set_atp_saldo()
{
    if (isset($_REQUEST)) {

        TrendeeCoupons::$atp_saldo = $_REQUEST['saldo'];
        $coupons = TrendeeCoupons::$coupons;
        if (empty($coupons)):
            return false;
        endif;

        foreach ($coupons as $coupon) {
            $couponCode = $coupon["code"];
            update_atp_saldo($couponCode);
        }
    }
    wp_die();
}

function update_atp_saldo($couponCode)
{
    global $wpdb;

    $atp_saldo = TrendeeCoupons::$atp_saldo;
    $wpdb->update(
        'wp_coupons_data',
        array(
            'atp_saldo' => $atp_saldo,
        ),
        array(
            'id_user' => get_current_user_id(),
            'coupon_code' => $couponCode
        )
    );
}
