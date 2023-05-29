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
    updateSaldoOnMetaKey($atpData->meta_value);
}

function updateSaldoOnMetaKey($metaValueSaldo)
{
    global $wpdb;
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
