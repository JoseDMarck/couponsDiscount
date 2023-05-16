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
/*  Ejecutamos el script para obtener el saldo del LocalStorage
/*----------------------------------------------------------------*/
function runScriptToGetLocalStorageSaldo()
{

    wp_enqueue_script('coupon_discount', plugins_url('/public/js/scripts.js', __FILE__), array('jquery'), '20200110');

    wp_localize_script(
        'coupon_discount',
        'ajax_object',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'whatever' => ''
        )
    );
    wp_enqueue_script('coupon_discount');
}
add_action('init', 'runScriptToGetLocalStorageSaldo', 1);



/*----------------------------------------------------------------
/*  Ejecutamos el script para obtener el saldo del LocalStorage
/*----------------------------------------------------------------*/
function save_client_on_db()
{

    if (isset($_REQUEST)) {
        require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';
        $CPD = new CouponsDiscount();
        $CPD->saveClientSaldoOnDB($_REQUEST['saldo']);
    }

    wp_die();
}
add_action('wp_ajax_save_client_on_db', 'save_client_on_db', 1);



/*----------------------------------------------------------------
/*  Aplicar el cupon de descuento
/*----------------------------------------------------------------*/
function applyDiscountCoupon()
{

    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount.php';
    $CPD = new CouponsDiscount();

    /*----------------------------------------------------------------
    /*  1.- Verificar si el cliente tiene pedidos
    /*----------------------------------------------------------------*/

    $clientHasOrder = $CPD->clientHasOrder();
    if (!$clientHasOrder):
        return false;
    endif;

    /*----------------------------------------------------------------
    /* 2.- Obtener el ultimo pedido (completado o precesando) del cliente 
    /*----------------------------------------------------------------*/
    $CPD->getLastClientOrder();

    /*----------------------------------------------------------------
    /* 3.- Si Notiene datos; se inserta
    /*----------------------------------------------------------------*/
    $checkIsUserHasData = $CPD->checkIsUserHasData();
    if (!$checkIsUserHasData):
        $CPD->insertLastClientOrder(); //Si no tiene datos insertarlos en DB
    endif;

    /*----------------------------------------------------------------
    /* 4.- Si ya tiene data setteamos user data
    /*----------------------------------------------------------------*/
    if ($checkIsUserHasData):
        $CPD->setUserData();

    endif;


    $checkIsCouponUsed = $CPD->checkIsCouponUsed();
    if ($checkIsCouponUsed):
        return false;
    endif;

    /*----------------------------------------------------------------
    /* 3.- Si ya tiene datos se actualiza
    /*----------------------------------------------------------------*/

    if ($checkIsUserHasData):
        $CPD->updateLastClientOrder(); //Si ya tiene datos actualizarlos en DB
    endif;

    /*----------------------------------------------------------------
    /* 4.- Get Coupon Data
    /*----------------------------------------------------------------*/
    $getCouponsData = $CPD->getCouponsData();
    if (!$getCouponsData):
        return false;
    endif;

    /*----------------------------------------------------------------
    /* 5.- Get Coupon Data
    /*----------------------------------------------------------------*/
    $getDiscount = $CPD->getCouponDiscount();
    if (!$getDiscount):
        return false;
    endif;

    /*----------------------------------------------------------------
    /* 6.- Update Accumulated saving on DB
    /*----------------------------------------------------------------*/
    $CPD->updateLastClientOrder();

}
//add_action('init', 'applyDiscountCoupon', 10);

add_action('woocommerce_payment_complete', 'applyDiscountCoupon', 1);
//add_action('woocommerce_thankyou', 'applyDiscountCoupon', 1);





/*----------------------------------------------------------------
/*  Cuando se desactiva el plugin 
/*----------------------------------------------------------------*/
function deactivateTCPlugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-coupons-discount-deactivator.php';
    TrendeeCouponsDeactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivateTCPlugin');
