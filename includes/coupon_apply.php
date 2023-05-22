<?php


add_action("woocommerce_thankyou", "getCouponDiscount");
function getCouponDiscount()
{


    $coupons = TrendeeCoupons::$coupons;

    //print_r($coupons);

    if (empty($coupons)):
        return false;
    endif;

    $currentCoupon = 0;


    foreach ($coupons as $coupon):
        $couponData = array(
            "minimum_ammount" => $coupon["minimum_amount"],
            "discount_available" => $coupon["amount"],
            "coupon_code" => $coupon["code"],
            "coupon_type" => $coupon["type"],
        );


        $couponIsUsed = checkCouponIsAlreadyUse($coupon["code"]);

        if (!empty($couponIsUsed)):
            return false;
        endif;


        if (checkCouponLimit($couponData)):
            calculateDiscount($couponData, $currentCoupon);
            $currentCoupon++;
        endif;


    endforeach;

}




function calculateDiscount($coupon, $currentCoupon)
{


    $totalLastOrder = TrendeeCoupons::$totalLastOrder;
    $ATPSaldo = TrendeeCoupons::$atp_saldo;
    $couponType = $coupon["coupon_type"];


    // Si no tiene ahorro acumulado termina el proceso
    if ($ATPSaldo === 0 or $ATPSaldo === Null):
        return false;
    endif;



    $accumulatedSaving = TrendeeCoupons::$accumulatedSavings + $ATPSaldo; // 0 + 200 = 200

    // Sumar el descuento obtenido a mi ahorro acumulado 
    if ($currentCoupon === 0):

        if ($couponType === "discount_on_last_savings_porcent") {
            $obtainedDiscount = ($coupon["discount_available"] / 100) * $accumulatedSaving; //(15/100)*200 = 39 
        } else {
            $obtainedDiscount = $coupon["discount_available"]; //$200
        }
        $discountApplied = $obtainedDiscount + $accumulatedSaving; // 30 + 200 = 230
        $atp_current_saldo = $ATPSaldo;

        TrendeeCoupons::$new_atp_saldo = round($discountApplied, 2); // 230


    else:

        if ($couponType === "discount_on_last_savings_porcent") {
            $obtainedDiscount = ($coupon["discount_available"] / 100) * TrendeeCoupons::$new_atp_saldo; //(10/100)*230 = 23 
        } else {
            $obtainedDiscount = $coupon["discount_available"]; //$200
        }

        $discountApplied = TrendeeCoupons::$new_atp_saldo + $obtainedDiscount; // 230 + 23 = 253
        $atp_current_saldo = TrendeeCoupons::$new_atp_saldo;
        TrendeeCoupons::$new_atp_saldo = round($discountApplied, 2); // 253
    endif;


    array_push(
        TrendeeCoupons::$applyCouponData,
        array(
            "accumulated_savings" => round($discountApplied, 2),
            "obtained_discount" => round($obtainedDiscount, 2),
            "atp_current_saldo" => round($atp_current_saldo, 2),
            "coupon_code" => $coupon["coupon_code"],
            "coupon_type" => $coupon["coupon_type"],
            "is_coupon_used" => 1,
            "discount_available" => $coupon["discount_available"]
        )
    );



}


/*----------------------------------------------------------------
/* Revisamos si la ultima compra es mayor que limite del cupon
/*----------------------------------------------------------------*/
function checkCouponLimit($coupon)
{
    $totalLastOrder = TrendeeCoupons::$totalLastOrder;


    if ($totalLastOrder < $coupon["minimum_ammount"]):
        return false;
    endif;

    return true;
}




/*----------------------------------------------------------------
/* Revisamos si el usario ya uso el cupon
/*----------------------------------------------------------------*/
function checkCouponIsAlreadyUse($coupon)
{

    global $wpdb;
    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM wp_coupons_data WHERE id_user = %d AND coupon_code = %s AND is_coupon_used = 1",
            get_current_user_id(),
            $coupon
        )
    );


    return $result;
}
