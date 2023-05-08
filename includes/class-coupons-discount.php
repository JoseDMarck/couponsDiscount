<?php


class CouponsDiscount
{

    public $last_total_order;
    public $user_id;


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

        // $user_id = get_current_user_id();
        // $customer = new WC_Customer($user_id);
        // $last_order = $customer->get_last_order();

        // //$order = wc_get_order(141);
        // print_r($last_order);
        // print_r($last_order->get_total());


        $args = array(
            'post_type' => 'shop_order',
            'posts_per_page' => -1,
            'meta_value' => get_current_user_id(),
            'post_status' => array("wc-processing", "wc-completed"), //array("wc-processing", "wc-completed"),
        );

        $orders = wc_get_orders($args);

        //print_r($orders);

        if ($orders) {
            $last_order = reset($orders);

            $order = wc_get_order($last_order->ID);

            echo $order->get_total();



            //$this->update_last_order($this->last_total_order, $this->user_id);
        }

        // $args = array(
        //     'numberposts' => 1,
        //     'meta_key' => '_customer_user',
        //     'meta_value' => get_current_user_id(),
        //     'post_type' => wc_get_order_types(),
        //     'post_status' => array_keys(wc_get_order_statuses()), //array("wc-processing", "wc-completed"),
        // );

        // $orders = get_posts($args);




        // print_r($last_order);

        // if ($orders) {
        //     $last_order = reset($orders);

        //     $order = wc_get_order($last_order->ID);

        //     echo $order->get_total();



        //     //$this->update_last_order($this->last_total_order, $this->user_id);
        // }
    }


    public function check_is_order_exists()
    {
        global $wpdb;
        $table = 'wp_discount_quantities';
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table WHERE id_user = %s",
                $this->user_id
            )
        );

        if (!empty($result)) {
            // Record for company name already exists, do update here
            echo "<h1>Existe  $this->user_id </h1>";
        } else {
            // Record does not exist, do insert here
            echo "<h1>No Existe  $this->user_id</h1>";

        }
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
