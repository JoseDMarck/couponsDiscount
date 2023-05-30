<?php

add_action('init', 'gd__coupon_delete', 10);
function gd__coupon_delete()
{

    $args = array(
        'post_type' => 'shop_coupon',
        'post_status' => 'trash',
        'posts_per_page' => -1
    );
    $coupons = get_posts($args);

    if (empty($coupons)):
        return false;
    endif;


    foreach ($coupons as $coupon):
        $coupon = new WC_Coupon($coupon->ID);

        if (!empty(checkIsCouponDelete($coupon->code))):
            DeleteDataCoupon($coupon);
        endif;

    endforeach;

}


function checkIsCouponDelete($couponCode)
{
    global $wpdb;
    $query = $wpdb->prepare(
        "SELECT * FROM wp_coupons_data WHERE coupon_code = %s",
        $couponCode,
    );

    return $query;
}
function DeleteDataCoupon($coupon)
{

    global $wpdb;
    $wpdb->delete('wp_coupons_data', array('coupon_code' => $coupon->code));


}
