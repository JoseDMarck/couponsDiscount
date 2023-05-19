<?php

add_action('init', "getCouponsData");

function getCouponsData()
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

    $i = 0;
    $couponLastSavin = array();

    foreach ($coupons as $coupon) {

        $coupon = new WC_Coupon($coupon->ID);

        if ($coupon->discount_type === 'discount_on_last_savings_porcent'):
            $couponLastSavin[$i]['code'] = $coupon->code;
            $couponLastSavin[$i]['type'] = $coupon->discount_type;
            $couponLastSavin[$i]['amount'] = $coupon->amount;
            $couponLastSavin[$i]['minimum_amount'] = $coupon->minimum_amount;
            $couponLastSavin[$i]['usedBy'] = $coupon->get_used_by();
            $i++;
        endif;
    }

    TrendeeCoupons::setCoupons($couponLastSavin);

}
