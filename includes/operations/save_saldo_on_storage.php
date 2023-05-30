<?php

add_action('woocommerce_thankyou', 'saveSaldoOnLocalStorage');
function saveSaldoOnLocalStorage()
{

    $coupons = TrendeeCoupons::$applyCouponData;
    $couponsApply = array();

    foreach ($coupons as $coupon):

        $discount = $coupon["discount_available"] . "%";
        if ($coupon["coupon_type"] !== "discount_on_last_savings_porcent"):
            $discount = "$" . $coupon["discount_available"];
        endif;

        array_push(
            $couponsApply,
            array("discount_available" => $discount)
        );

    endforeach;

    wp_enqueue_script('coupon_discount_save_saldo', plugins_url('/public/js/gd__save_saldo.js', TC__FILE__), array('jquery'), '20200110');

    wp_localize_script(
        'coupon_discount_save_saldo',
        'wp_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            //'generateSaldo' => TrendeeCoupons::$new_atp_saldo,
            'generateSaldo' => TrendeeCoupons::$meta_value_saldo,
            'couponsApply' => $couponsApply
        )
    );
    wp_enqueue_script('coupon_discount_save_saldo');
}
