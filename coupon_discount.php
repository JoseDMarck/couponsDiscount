<?php
/*
 * Plugin Name:       Trendee Coupons Discount
 * Plugin URI:        https://github.com/JoseDMarck/couponsDiscount
 * Description:       Aplicar cupones sobre el descuento ahorrado
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            @josedsamamago
 * Author URI:        https://github.com/JoseDMarck/
 * License:           GPL v2 or later
 * License URI:       https://github.com/JoseDMarck/couponsDiscount/blob/main/licence.txt
 */



/*----------------------------------------------------------------
// Evitar que se llame directamente
/*----------------------------------------------------------------*/
if (!defined('WPINC')) {
    die;
}

/*----------------------------------------------------------------
/* Cuando se activa llama a create_discount_table 
/*----------------------------------------------------------------*/
function ActivateTCPlugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-activator.php';
    TrendeeCouponsActivator::activate();

}
register_activation_hook(__FILE__, 'ActivateTCPlugin');


/*----------------------------------------------------------------
/*  Activa la opción de "Descuento sobre último ahorro" 
/*----------------------------------------------------------------*/
function activateSavingsOption()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-activator.php';
    $activateSavingsOption = new TrendeeCouponsActivator();

}
add_action('init', 'activateSavingsOption');



/*----------------------------------------------------------------
/*  Obtener la ultima orden
/*----------------------------------------------------------------*/
function getLastClientOrder()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';


    $couponsDiscount = new CouponsDiscount();
    $couponsDiscount->getLastClientOrder();


}
add_action('init', 'getLastClientOrder', 6);


// /*----------------------------------------------------------------
// /*  Revisar si la orden existe en DB
// /*----------------------------------------------------------------*/
function checkIsOrderExists()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';

    $couponsDiscount = new CouponsDiscount();
    $couponsDiscount->checkIsOrderExists();

    if (!empty($couponsDiscount->checkIsOrderExists())):
        $couponsDiscount->updateLastClientOrder();
    else:
        $couponsDiscount->insertLastClientOrder();
    endif;


}
add_action('init', 'checkIsOrderExists', 10);



/*----------------------------------------------------------------
/*  Actualizar en base de datos
/*----------------------------------------------------------------*/
function updateLastClientOrder()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';
    $couponsDiscount = new CouponsDiscount();
    $couponsDiscount->updateLastClientOrder();
}
//add_action('init', 'updateLastClientOrder');

function my_custom_coupon_function($coupon_code)
{

    global $wpdb;
    $table_name = 'wp_discount_quantities';
    $coupon = new WC_Coupon("dbarjzw3");
    $type = $coupon->discount_type;
    $amount = $coupon->amount;
    $minimum_amount = $coupon->minimum_amount;

    // Check if the coupon code is the one you're interested in
    if ($coupon_code == 'dbarjzw3') {
        // Call your custom function here

        $wpdb->update(
            $table_name,
            array('quantity' => 300),
            array('id' => 3)
        );

        echo "<h1>Cupón aplicado</h1>";
        echo $type;
        echo $amount;
        echo $minimum_amount;
    }
}
add_action('woocommerce_applied_coupon', 'my_custom_coupon_function', 10, 1);

/*----------------------------------------------------------------
/*  Cuando se desactiva el plugin 
/*----------------------------------------------------------------*/
function deactivateTCPlugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-deactivator.php';
    TrendeeCouponsDeactivator::deactivate();


}
register_deactivation_hook(__FILE__, 'deactivateTCPlugin');
