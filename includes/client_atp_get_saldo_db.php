<?php

add_action('init', 'getATPSaldoFromDB');
function getATPSaldoFromDB()
{
    $coupons = TrendeeCoupons::$coupons;

    if (empty($coupons)):
        return false;
    endif;

    foreach ($coupons as $coupon):

        $couponCode = $coupon["code"];
        $haveCoupon = checkIsUserHasCoupon($couponCode);

        if (empty($haveCoupon)):
            inserUserInfo($coupon);
        endif;

    endforeach;

}
