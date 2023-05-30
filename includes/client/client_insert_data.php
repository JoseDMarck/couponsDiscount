<?php

add_action('init', 'insertClientData');
function insertClientData()
{
    $coupons = TrendeeCoupons::$coupons;

    //print_r($coupons);

    if (empty($coupons)):
        return false;
    endif;

    foreach ($coupons as $coupon):
        $couponCode = $coupon["code"];
        $haveCoupon = checkIsUserHasCoupon($couponCode);

        if (empty($haveCoupon)):
            inserUserInfo($coupon);
        endif;

        $atpSaldo = $haveCoupon[0];
        TrendeeCoupons::$atp_saldo = $atpSaldo->atp_saldo;

    endforeach;

}


/*----------------------------------------------------------------
/* Revisamos si el usario tiene cupones 
/*----------------------------------------------------------------*/
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

/*----------------------------------------------------------------
/* Se insertan los datos de los cupones 
/*----------------------------------------------------------------*/
function inserUserInfo($coupon)
{

    $current_user = wp_get_current_user();

    if (get_current_user_id() > 0):
        global $wpdb;
        $totalLastOrder = TrendeeCoupons::$totalLastOrder;
        $couponValue = "";

        if ($coupon["type"] === "discount_on_last_savings_porcent"):
            $couponValue = $coupon["amount"] . "%";

        else:
            $couponValue = "$" . $coupon["amount"];

        endif;

        $wpdb->insert(
            'wp_coupons_data',
            array(
                'last_purchase_mount' => $totalLastOrder,
                'coupon_code' => $coupon["code"],
                'coupon_type' => $coupon["type"],
                'coupon_value' => $couponValue,
                'id_user' => get_current_user_id(),
                "user_name" => $current_user->user_firstname
            ),
        );

    endif;

}
