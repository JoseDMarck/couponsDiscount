<?php

add_action('woocommerce_thankyou', 'getSaldoOnMetaKey');


function getSaldoOnMetaKey()
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM wp_usermeta WHERE user_id = %d AND meta_key = %s",
            get_current_user_id(),
            "atp_saldo",
        )
    );

    $atpData = $result[0];
    //print_r($atpData->meta_value);
    updateSaldoOnMetaKey($atpData->meta_value);
    //return $result;

}

function updateSaldoOnMetaKey($metaValueSaldo)
{
    global $wpdb;

    //print_r("updateSaldoOnMetaKey");

    $atpSaldo = TrendeeCoupons::$new_atp_saldo;
    $newATPSaldo = $metaValueSaldo + $atpSaldo;

    TrendeeCoupons::$meta_value_saldo = $newATPSaldo;
    $wpdb->update(
        'wp_usermeta',
        array(
            'meta_value' => $newATPSaldo,
        ),
        array(
            'user_id' => get_current_user_id(),
            'meta_key' => "atp_saldo",
        )
    );

}
