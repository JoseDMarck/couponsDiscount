<?php

add_action("init", "getCouponDiscount");
function getCouponDiscount()
{

    //print_r(TrendeeCoupons::$coupons);

    $coupons = TrendeeCoupons::$coupons;

    if (empty($coupons)):
        return false;
    endif;

    foreach ($coupons as $coupon) {



        $couponData = array(
            "minimum_ammount" => $coupon["minimum_amount"],
            "discount_available" => $coupon["amount"],
            "coupon_code" => $coupon["code"],
            "coupon_type" => $coupon["type"],
        );

        //print_r($couponData);
        calculateDiscount($couponData);

    }





    // $couponData = $this->couponsData;
    // $minimumCouponAmount = $couponData[0]['minimum_amount']; //  $400
    // $discountAvailable = $couponData[0]['amount']; // 10% minimun
    // $couponCode = $couponData[0]['name']; // 10% minimun
    // $tp_saldo = $this->userData->tp_saldo; // $200
    // $coupon_type = $couponData[0]['type'];
    // $accumulatedSavings = $this->userData->accumulated_savings + $tp_saldo; // $200


    // //Si el último pedido es mayor al monto minimo permitido en el cupon 
    // if ($this->lastTotalOrder < $minimumCouponAmount):
    //     return false;
    // endif;

    // // Si no tiene ahorro acumulado termina el proceso
    // if ($tp_saldo === 0):
    //     return false;
    // endif;

    // //Operaciones: Aplicar 10% (discountAvailable) sobre mi ultimo ahorro (accumulatedSavings)
    // $obtainedDiscount = ($discountAvailable / 100) * $accumulatedSavings; //(10/100)*200

    // //Sumar el descuento obtenido a mi ahorro acumulado 
    // $discountApply = $obtainedDiscount + $accumulatedSavings;


    // //Actualizar objeto de user 
    // $this->userData->accumulated_savings = $discountApply;
    // $this->userData->is_coupon_used = 1;
    // $this->userData->coupon_code = $couponCode;
    // $this->userData->coupon_value = $discountAvailable;
    // $this->userData->coupon_type = $coupon_type;



    //return true;
}


function calculateDiscount($coupon)
{

    $totalLastOrder = TrendeeCoupons::$totalLastOrder;
    $ATPSaldo = TrendeeCoupons::$atp_saldo;
    $accumulatedSavings = TrendeeCoupons::$accumulatedSavings + $ATPSaldo;


    echo "<br>";
    echo "totalLastOrder" . " " . $totalLastOrder;
    echo "<br>";

    echo "ATPSaldo XXX" . " " . $ATPSaldo;
    echo "<br>";

    echo "coupon_code" . " " . $coupon["coupon_code"];
    echo "<br>";
    echo "minimum_ammount" . " " . $coupon["minimum_ammount"];


    //Si el último pedido es mayor al monto minimo permitido en el cupon 
    if ($totalLastOrder < $coupon["minimum_ammount"]):
        return false;
    endif;

    // Si no tiene ahorro acumulado termina el proceso
    if ($ATPSaldo === 0):
        return false;
    endif;

    // echo "calculateDiscount()";
}
