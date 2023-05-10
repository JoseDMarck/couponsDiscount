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
/*  Aplicar el cupon de descuento
/*----------------------------------------------------------------*/
function applyDiscountCoupon()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';
    $CPD = new CouponsDiscount();

    //1.- Verificar si el cliente tiene pedidos
    if (!$CPD->clientHasOrder()):
        return false;
    endif;

    //2.- Obtener el ultimo pedido (completado o precesando) del cliente  
    $CPD->getLastClientOrder();

    //3.- Revisar si el usuario tiene datos en la tabla :
    if (!$CPD->checkIsUserHasData()):
        $CPD->insertLastClientOrder(); //Si no tiene datos insertarlos en DB
    else:
        $CPD->updateLastClientOrder(); //Si ya tiene datos actualizarlos en DB
    endif;


    //4.- Get Coupon Data

    if (!$CPD->getCouponsData()):
        return false;
    endif;

    print_r($CPD->getCouponsData());




    echo "<h1>user ID: $CPD->userID</h1>";
    echo "<h1>Total: $CPD->lastTotalOrder</h1>";
    echo "<h1>next fucntion()</h1>";

}





add_action('init', 'applyDiscountCoupon');



/*----------------------------------------------------------------
/*  Cuando se desactiva el plugin 
/*----------------------------------------------------------------*/
function deactivateTCPlugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-deactivator.php';
    TrendeeCouponsDeactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivateTCPlugin');
