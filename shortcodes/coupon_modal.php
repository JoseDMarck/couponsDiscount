<?php
add_shortcode('coupon_modal_shortcode', 'coupon_modal_shortcode');
function coupon_modal_shortcode()
{

    ob_start();
    include PLUGIN_PATH . 'public/php/gd__modal_coupon.php';
    $modalAlert = ob_get_clean();

    return $modalAlert;
}


/*----------------------------------------------------------------
/*  Agregamos el shortcode html de alerta en el header  
/*----------------------------------------------------------------*/


add_action('wp_head', 'insert_alert_shortcode');
function insert_alert_shortcode()
{
    echo do_shortcode('[coupon_modal_shortcode]');
}
