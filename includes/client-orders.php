<?php


require_once(ABSPATH . 'wp-load.php');
include_once(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php');


add_action('init', "clientHasOrder");
function clientHasOrder()
{
    $args = array(
        'post_type' => 'shop_order',
        'posts_per_page' => -1,
        'customer_id' => get_current_user_id(),
    );

    $orders = wc_get_orders($args);

    if (empty($orders)):
        return false;
    endif;

    getTotalFromLastOrder();

}

function getTotalFromLastOrder()
{
    $args = array(
        'post_type' => 'shop_order',
        'posts_per_page' => -1,
        'customer_id' => get_current_user_id(),
    );

    $orders = wc_get_orders($args);

    $clientOrder = array();
    $i = 0;
    foreach ($orders as $order):
        if ($order->status === "completed" || $order->status === "processing"):
            $clientOrder[$i]["total"] = $order->total;
            $clientOrder[$i]["customer"] = get_current_user_id();
            $clientOrder[$i]["status"] = $order->status;
            $i++;
        endif;
    endforeach;

    $totalLastOrder = $clientOrder[0]['total'];
    $TrendeeCoupons = new TrendeeCoupons();
    $TrendeeCoupons->setTotalLastOrder($totalLastOrder);
    $TrendeeCoupons->totalLastOrder;

}
