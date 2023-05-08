<?php

class CouponsDiscount
{

    public $last_total_order;
    public $user_id;

    public $STATUS_COMPLETED = 'completed';
    public $STATUS_PROCESSING = 'processing';


    public function __construct()
    {
    }
    public function create_discount_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table = 'wp_discount_quantities';
        $sql = "CREATE TABLE  $table (
            id BIGINT(20) NOT NULL auto_increment,
            last_purchase_mount FLOAT(20) NOT NULL,
            id_user int(11) NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    public function get_last_order()
    {



        $args = array(

            'post_type' => 'shop_order',
            'posts_per_page' => -1,
            'customer_id' => get_current_user_id(),
        );


        $orders = wc_get_orders($args);


        //print_r($orders);

        if ($orders) {

            $orderFilters = array();
            foreach ($orders as $order) {

                if ($order->status === $this->STATUS_COMPLETED || $order->status === $this->STATUS_PROCESSING) {
                    $orderFilters = array('status' => $order->status, 'total' => $order->total);
                }
            }

            print_r($orderFilters);

            $last_order = reset($orders);

            $orderInfo = new CouponsDiscount();
            $orderInfo->last_total_order = $order->get_total();
            $orderInfo->user_id = get_current_user_id();



            //print_r($order->total);

            //print_r($order->status);


            //echo "<h1>Resultados $orderInfo->last_total_order</h1>";
            return $orderInfo;

        } else {
            // echo "<h1>Sin Resultados</h1>";
            return "No hay datos";
        }


        // echo "<h4>total =>  $orderInfo->last_total_order </h4>";
        // echo "<h4>user_id =>  $orderInfo->user_id </h4>";



    }



    public function check_is_order_exists()
    {


        $order_info = $this->get_last_order();
        // print_r($this->get_last_order());

        echo "<h4>total check_is_order_exists() =>  $order_info->last_total_order </h4>";
        echo "<h4>user_id check_is_order_exists() =>  $order_info->user_id </h4>";

        // global $wpdb;
        // $table = 'wp_discount_quantities';
        // $result = $wpdb->get_results(
        //     $wpdb->prepare(
        //         "SELECT * FROM $table WHERE id_user = %s",
        //         $this->user_id
        //     )
        // );

        // if (!empty($result)) {
        //     // Record for company name already exists, do update here
        //     echo "<h1>Existe  $this->user_id </h1>";
        // } else {
        //     // Record does not exist, do insert here
        //     echo "<h1>No Existe  $this->user_id</h1>";

        // }
    }
    public function update_last_order($mount, $user_id)
    {

        global $wpdb;
        $table = 'wp_discount_quantities';
        $wpdb->update(
            $table,
            array('last_purchase_mount' => $mount),
            array('id_user' => $user_id)
        );

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


    }
}
