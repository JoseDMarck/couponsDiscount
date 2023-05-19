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


    //echo TrendeeCoupons::$str;
    //getTotalFromLastOrder($orders[0]->ID);
    // require_once plugin_dir_path(TC__FILE__) . 'coupon_discount.php';
    // TrendeeCoupons::setTotalLastOrder($test);
}



//add_action('init', 'getTotalFromLastOrder');
function getTotalFromLastOrder()
{


    echo TrendeeCoupons::$str;




    // $order = wc_get_order($orderID);

    // // var_dump($order);

    // echo $order->total;
    // $TrendeeCoupons = new TrendeeCoupons();
    // $TrendeeCoupons->setTotalLastOrder($order->total);


    // $args = array(
    //     'post_type' => 'shop_order',
    //     'posts_per_page' => -1,
    //     'customer_id' => get_current_user_id(),
    // );

    // $orders = wc_get_orders($args);
    // var_dump($orders);


    //var_dump(count($orders));
    //var_dump($orders[0][]);
    // $clientOrder = array();
    // $i = 0;
    // foreach ($orders as $order):
    //     echo $order->total;
    //     echo "<br>";
    //     // $clientOrder[$i]["total"] = $order->total;
    //     // $clientOrder[$i]["customer"] = get_current_user_id();
    //     // $clientOrder[$i]["status"] = $order->status;
    //     $i++;

    // endforeach;

    //var_dump($clientOrder);
    //$totalLastOrder = $clientOrder[0]['total'];
    //$TrendeeCoupons = new TrendeeCoupons();
    //$TrendeeCoupons->setTotalLastOrder($totalLastOrder);
    // $TrendeeCoupons->getTotalLastOrder();
    //$TrendeeCoupons->totalLastOrder;
    //return $totalLastOrder;
}
