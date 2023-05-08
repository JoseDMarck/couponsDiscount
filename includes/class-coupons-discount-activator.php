<?php

require_once 'class-coupons-discount.php';
class Coupons_Discount_Activator extends CouponsDiscount
{

    public function __construct()
    {
        add_filter('woocommerce_coupon_discount_types', array($this, 'set_last_saving_discount'));
    }

    public static function activate()
    {
        $createTable = new CouponsDiscount();
        $createTable->create_discount_table();
    }

    public static function set_last_saving_discount($discount_types)
    {
        $discount_types['discount_on_last_savings_porcent'] = __('Descuento sobre último ahorro', 'woocommerce');
        return $discount_types;
    }
}
