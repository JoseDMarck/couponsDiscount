<?php

add_action('init', 'getClientLastTotalOrder');
function getClientLastTotalOrder()
{
    $args = array(
        'post_type' => 'shop_order',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_customer_user',
                'value' => get_current_user_id(),
                'compare' => '='
            )
        ),
        'post_status' => array('wc-processing', 'wc-completed')
    );

    $orders = get_posts($args);

    if (empty($orders)):
        return false;
    endif;

    $order_id = $orders[0]->ID;
    $order = wc_get_order($order_id);

    TrendeeCoupons::setTotalLastOrder($order->total);
}
