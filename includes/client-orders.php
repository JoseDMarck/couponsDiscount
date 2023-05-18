<?php


add_action('init', 'getClientOrders');
function getClientOrders()
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

    var_dump($orders[0]->ID);
    // /$orders = wc_get_orders($args);


    //getTotalFromLastOrder(237);

}


add_action(
    'thesis_hook_before_post',
    function ($test) {

        // $TrendeeCoupons = new TrendeeCoupons();
        // $TrendeeCoupons->setTotalLastOrder(99);
    
        // TrendeeCoupons::setTotalLastOrder($test);
    }
);


do_action('thesis_hook_before_post', "holaWold");



$this->setTotalLastOrder(99);
echo $this->totalLastOrder;
// add_action('thesis_hook_before_post', 'getTotalFromLastOrder', 10, 1);

// $test = " hola mundo";




function getTotalFromLastOrder($orderID)
{

    echo "getTotalFromLastOrder()";


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
