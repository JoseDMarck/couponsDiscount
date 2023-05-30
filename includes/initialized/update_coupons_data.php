<?php

add_action('woocommerce_coupon_options_save', 'gd__coupon_update', 10);
function gd__coupon_update()
{


    $args = array(
        'post_type' => 'shop_coupon',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );
    $coupons = get_posts($args);

    if (empty($coupons)):
        return false;
    endif;


    foreach ($coupons as $coupon):
        $coupon = new WC_Coupon($coupon->ID);

        //print_r($coupon);
        if (!empty(checkIsCouponExists($coupon->code))):
            updateNewDataCoupon($coupon);
        endif;


    endforeach;

}


function checkIsCouponExists($couponCode)
{
    global $wpdb;
    $query = $wpdb->prepare(
        "SELECT * FROM wp_coupons_data WHERE coupon_code = %s",
        $couponCode,
    );

    return $query;
}
function updateNewDataCoupon($coupon)
{

    $couponValue = "";
    if ($coupon->discount_type === "discount_on_last_savings_porcent"):
        $couponValue = $coupon->amount . "%";
    else:
        $couponValue = "$" . $coupon->amount;
    endif;

    global $wpdb;
    $wpdb->update(
        'wp_coupons_data',
        array(
            'coupon_value' => $couponValue,
            'coupon_code' => $coupon->code,
            'coupon_type' => $coupon->discount_type
        ),
        array(
            'coupon_code' => $coupon->code,
        )
    );
}
