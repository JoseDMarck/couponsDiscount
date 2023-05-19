<?php

add_action('init', 'insertClientData');
function insertClientData()
{

    //print_r(TrendeeCoupons::$totalLastOrder);
    //print_r(TrendeeCoupons::$coupons);

    $coupons = TrendeeCoupons::$coupons;
    //print_r($coupons);

    foreach ($coupons as $coupon) {

        $couponCode = $coupon["code"];
        $haveCoupon = checkIsUserHasCoupon($couponCode);

        if (empty($haveCoupon)):
            inserUserInfo($coupon);
        endif;





        // if ($coupon->discount_type === 'discount_on_last_savings_porcent'):
        //     $couponLastSavin[$i]['code'] = $coupon->code;
        //     $couponLastSavin[$i]['type'] = $coupon->discount_type;
        //     $couponLastSavin[$i]['amount'] = $coupon->amount;
        //     $couponLastSavin[$i]['minimum_amount'] = $coupon->minimum_amount;
        //     $couponLastSavin[$i]['usedBy'] = $coupon->get_used_by();

        // endif;
    }

    // global $wpdb;
    // $wpdb->update(
    //     'wp_discount_quantities',
    //     array(
    //         'last_purchase_mount' => $this->lastTotalOrder,
    //         'accumulated_savings' => $this->userData->accumulated_savings,
    //         'is_coupon_used' => $this->userData->is_coupon_used,
    //         'coupon_code' => $this->userData->coupon_code,
    //         'coupon_value' => $this->userData->coupon_value,
    //         'coupon_type' => $this->userData->coupon_type,

    //     ),
    //     array('id_user' => get_current_user_id())
    // );

    // return $this->userData;

}

function checkIsUserHasCoupon($coupon)
{
    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM wp_coupons_data WHERE id_user = %d AND coupon_code = %s",
            get_current_user_id(),
            $coupon
        )
    );

    return $result;
}



function inserUserInfo($coupon)
{

    if (get_current_user_id() > 0):
        global $wpdb;
        $totalLastOrder = TrendeeCoupons::$totalLastOrder;
        $wpdb->insert(
            'wp_coupons_data',
            array(
                'last_purchase_mount' => $totalLastOrder,
                'coupon_code' => $coupon["code"],
                'coupon_type' => $coupon["type"],
                'coupon_value' => $coupon["amount"] . "%",
                'id_user' => get_current_user_id()
            ),
        );
    endif;

}
