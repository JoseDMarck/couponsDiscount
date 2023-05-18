<?php

add_filter('woocommerce_coupon_discount_types', 'setNewCouponTypes');

function setNewCouponTypes($discount_types)
{
    $discount_types['discount_on_last_savings_porcent'] = __('Descuento sobre último ahorro', 'woocommerce');
    return $discount_types;
}
