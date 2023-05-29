<?php


add_action('woocommerce_thankyou', 'updateClientData');
function updateClientData()
{

    $coupons = TrendeeCoupons::$applyCouponData;

    if (empty($coupons)):
        return false;
    endif;

    foreach ($coupons as $coupon):
        updateClientOnDatabase($coupon);
    endforeach;
}


function updateClientOnDatabase($coupon)
{
    global $wpdb;
    $wpdb->update(
        'wp_coupons_data',
        array(
            'accumulated_savings' => $coupon["accumulated_savings"],
            'obtained_discount' => $coupon["obtained_discount"],
            'atp_current_saldo' => $coupon["atp_current_saldo"],
            'is_coupon_used' => 1,
        ),
        array(
            'id_user' => get_current_user_id(),
            'coupon_code' => $coupon["coupon_code"]
        )
    );
}
