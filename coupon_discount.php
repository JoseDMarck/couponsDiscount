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
function activate_plugin_coupon_discount()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-activator.php';
    Coupons_Discount_Activator::activate();

}
register_activation_hook(__FILE__, 'activate_plugin_coupon_discount');





/*----------------------------------------------------------------
/*  Obtener la ultima orden
/*----------------------------------------------------------------*/
function get_last_client_order()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';


    $couponsDiscount = new CouponsDiscount();
    $couponsDiscount->get_last_order();


}
add_action('init', 'get_last_client_order', 10);


/*----------------------------------------------------------------
/*  Activa la opción de "Descuento sobre último ahorro" 
/*  en tipo de descuento
/*----------------------------------------------------------------*/
function set_last_saving_discount()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-activator.php';
    $set_last_saving_discount = new Coupons_Discount_Activator();

}
//add_action('init', 'set_last_saving_discount');





// /*----------------------------------------------------------------
// /*  Revisar si la orden existe en DB
// /*----------------------------------------------------------------*/
function check_is_order_exists()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';

    $couponsDiscount = new CouponsDiscount();
    $couponsDiscount->check_is_order_exists();
}
//add_action('init', 'check_is_order_exists', 2);





/*----------------------------------------------------------------
/*  Actualizar en base de datos
/*----------------------------------------------------------------*/
function update_last_order()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';
    $couponsDiscount = new CouponsDiscount();
    $couponsDiscount->update_last_order();
}
//add_action('init', 'update_last_order');










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
function deactivate_coupon_discount()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-deactivator.php';
    Plugin_Name_Deactivator::deactivate();


}
register_deactivation_hook(__FILE__, 'deactivate_coupon_discount');
