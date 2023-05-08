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

if (!defined('WPINC')) {
    die;
}

include_once ABSPATH . 'wp-admin/includes/plugin.php';



function activate_plugin_coupon_discount()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-activator.php';
    Coupons_Discount_Activator::activate();
}


function deactivate_coupon_discount()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-deactivator.php';
    Plugin_Name_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_plugin_coupon_discount');
register_deactivation_hook(__FILE__, 'deactivate_coupon_discount');


// // Evitar que el plugin sea llamado directamente
// if (!defined('WPINC')) {
//     die;
// }


/**
 * Register the "book" custom post type
 */
function pluginprefix_setup_post_type()
{
    register_post_type('book', ['public' => true]);
}
add_action('init', 'pluginprefix_setup_post_type');


/**
 * Activate the plugin.
 */




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



function custom_discount_type($discount_types)
{
    $discount_types['discount_on_last_savings_porcent'] = __('Descuento sobre último ahorro', 'woocommerce');
    return $discount_types;
}

add_filter('woocommerce_coupon_discount_types', 'custom_discount_type', 11, 1);
