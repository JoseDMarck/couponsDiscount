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


if (!defined('ABSPATH')) {
    die('silent is golden...');
}

if (!class_exists('TrendeeCoupons')) {

    class TrendeeCoupons
    {

        public static $totalLastOrder;
        public static $coupons = array();
        public static $applyCouponData = array();
        public static $atp_saldo;
        public static $new_atp_saldo;
        public static $accumulatedSavings;

        public static $meta_value_saldo;

        public function __construct()
        {
            define("PLUGIN_PATH", plugin_dir_path(__FILE__));
            define('TC__FILE__', __FILE__);
        }
        public function initialize()
        {
            require_once PLUGIN_PATH . 'includes/initialized/register_admin_menu.php';
            require_once PLUGIN_PATH . 'includes/initialized/register_scritps.php';
            require_once PLUGIN_PATH . 'includes/initialized/truncate_coupons_data.php';
            require_once PLUGIN_PATH . 'includes/initialized/update_coupons_data.php';
            require_once PLUGIN_PATH . 'includes/initialized/delete_coupons_data.php';
            require_once PLUGIN_PATH . 'includes/initialized/register_activation_hook.php';
            require_once PLUGIN_PATH . 'includes/initialized/register_coupons_types.php';
            require_once PLUGIN_PATH . 'includes/initialized/client_atp_saldo.php';
            require_once PLUGIN_PATH . 'shortcodes/coupon_modal.php';

        }

        public function getClientData()
        {
            require_once PLUGIN_PATH . 'includes/client/client_orders.php';
            require_once PLUGIN_PATH . 'includes/client/client_coupons.php';
            require_once PLUGIN_PATH . 'includes/client/client_insert_data.php';
        }

        public function applyCoupons()
        {
            require_once PLUGIN_PATH . 'includes/operations/coupon_apply.php';
            require_once PLUGIN_PATH . 'includes/operations/client_data_update_bd.php';
            require_once PLUGIN_PATH . 'includes/operations/update_saldo_on_db.php';
            require_once PLUGIN_PATH . 'includes/operations/save_saldo_on_storage.php';


        }

    }

    $TrendeeCoupons = new TrendeeCoupons();
    $TrendeeCoupons->initialize();
    $TrendeeCoupons->getClientData();
    $TrendeeCoupons->applyCoupons();
}
