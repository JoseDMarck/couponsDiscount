<?php

class CouponsDiscount
{

    public $lastTotalOrder;
    public $userID;
    public $accumulatedSavings;
    public $isCouponUsed;
    public $couponCode;
    public $STATUS_COMPLETED = 'completed';
    public $STATUS_PROCESSING = 'processing';


    public function __construct()
    {
    }


    /*----------------------------------------------------------------
    /*  Crear tabla en DB
    /*----------------------------------------------------------------*/
    public function create_discount_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table = 'wp_discount_quantities';
        $sql = "CREATE TABLE  $table (
            id BIGINT(20) NOT NULL auto_increment,
            last_purchase_mount FLOAT(20) NOT NULL,
            id_user INT(11) NOT NULL,
            accumulated_savings FLOAT(20) NOT NULL DEFAULT 0,
            is_coupon_used INT(20) NOT NULL DEFAULT 0,
            coupon VARCHAR(40) NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }

    /*----------------------------------------------------------------
    /*  Obtener la ultima orden de cliente
    /*----------------------------------------------------------------*/
    public function clientHasOrder()
    {

        $args = array(
            'post_type' => 'shop_order',
            'posts_per_page' => -1,
            'customer_id' => get_current_user_id(),
            //'customer_id' => 99,
        );

        $orders = wc_get_orders($args);

        if (!empty($orders)):
            return true;
        endif;
    }

    /*----------------------------------------------------------------
    /*  Obtener la ultima orden de cliente
    /*----------------------------------------------------------------*/
    public function getLastClientOrder()
    {
        $args = array(
            'post_type' => 'shop_order',
            'posts_per_page' => -1,
            'customer_id' => get_current_user_id(),
            //'customer_id' => 99,
        );

        $orders = wc_get_orders($args);

        $clientOrder = array();
        $i = 0;
        foreach ($orders as $order):
            if ($order->status === $this->STATUS_COMPLETED || $order->status === $this->STATUS_PROCESSING):
                $clientOrder[$i]["total"] = $order->total;
                $clientOrder[$i]["customer"] = get_current_user_id();
                $clientOrder[$i]["status"] = $order->status;
                $i++;
            endif;
        endforeach;

        $orderInfo = new CouponsDiscount();
        $orderInfo->lastTotalOrder = $clientOrder[0]['total'];
        $orderInfo->userID = $clientOrder[0]['customer'];

        $this->setOrderData($clientOrder[0]['total'], $clientOrder[0]['customer']);

        return $orderInfo;

    }

    /*----------------------------------------------------------------
    /*  setter para guardar los valores total y user de la clase
    /*----------------------------------------------------------------*/
    public function setOrderData($total, $user_id)
    {
        $this->lastTotalOrder = $total;
        $this->userID = $user_id;
    }

    /*----------------------------------------------------------------
    /*  Revisar si la orden existe en la base de datos tabka (wp_discount_quantities)
    /*----------------------------------------------------------------*/
    public function checkIsUserHasData()
    {
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM wp_discount_quantities WHERE id_user = %s",
                $this->userID
            )
        );

        if (!empty($result)):
            return true;
        endif;

    }

    /*----------------------------------------------------------------
    /*  OBTENER LA INFORMACION DE CUPONES
    /*----------------------------------------------------------------*/

    public function getCouponsData()
    {

        $couponLastSavin = array();
        $args = array(
            'post_type' => 'shop_coupon',
            'post_status' => 'publish',
            'posts_per_page' => -1
        );
        $coupons_posts = get_posts($args);

        if (empty($coupons_posts)):
            return false;
        endif;

        $i = 0;
        foreach ($coupons_posts as $coupon_post) {

            if ($coupon_post->discount_type === 'discount_on_last_savings_porcent'):
                $coupon = new WC_Coupon($coupon_post->post_name);

                //Todo: preguntar si se puede adjuntar mas de un cupon
                $couponLastSavin[$i]['name'] = $coupon_post->post_name;
                $couponLastSavin[$i]['type'] = $coupon->discount_type;
                $couponLastSavin[$i]['amount'] = $coupon->amount;
                $couponLastSavin[$i]['minimum_amount'] = $coupon->minimum_amount;
                $couponLastSavin[$i]['usedBy'] = $coupon->get_used_by();
                $i++;
            endif;
        }

        if (empty($couponLastSavin)):
            return false;
        endif;

        return $couponLastSavin;

    }

    /*----------------------------------------------------------------
    /*  Aplica el Coupon de descuento
    /*----------------------------------------------------------------*/
    public function applyDiscountCoupon()
    {
        // $get_order_data = $this->checkIsUserHasData();
        // $couponGenerateDiscount = $this->doCouponOperations();
        // $newAccumulatedVale = $get_order_data[0]->accumulated_savings + $couponGenerateDiscount;

        // global $wpdb;
        // $order_info = $this->getLastClientOrder();

        // $wpdb->update(
        //     'wp_discount_quantities',
        //     array('accumulated_savings' => $newAccumulatedVale, 'is_coupon_used' => 1),
        //     array('id_user' => $order_info->userID)
        // );

        echo "<h4>applyDiscountCoupon()</h4>";

    }

    /*----------------------------------------------------------------
    /*  OBTENER LA INFORMACION DE CUPONES
    /*----------------------------------------------------------------*/

    public function doCouponOperations()
    {
        // $LastOrderTotal = $this->getLastClientOrder()->lastTotalOrder;
        // $getOrderData = $this->checkIsUserHasData();



        // $couponData = $this->getCouponsData();
        // $minimumCouponAmount = $couponData[0]['minimum_amount'];
        // $discountAvailable = $couponData[0]['amount'];


        // // //Si el último pedido es mayor a 
        // // if ($LastOrderTotal >= $minimumCouponAmount):
        // //     $totalDiscount = ($discountAvailable / 100) * $orderTotal;
        // //     return $totalDiscount;
        // // endif;

        return true;
    }




    /*----------------------------------------------------------------
    /*  Actualizar los nuevos datos de la ultima orden en la BD
    /*----------------------------------------------------------------*/
    public function updateLastClientOrder()
    {

        global $wpdb;
        $wpdb->update(
            'wp_discount_quantities',
            array('last_purchase_mount' => $this->lastTotalOrder),
            array('id_user' => $this->userID)
        );

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }

    /*----------------------------------------------------------------
    /*  Insertar los nuevos datos de la ultima orden en la BD
    /*----------------------------------------------------------------*/
    public function insertLastClientOrder()
    {
        global $wpdb;
        $wpdb->insert(
            'wp_discount_quantities',
            array(
                'last_purchase_mount' => $this->lastTotalOrder,
                'id_user' => $this->userID,
            )
        );
    }


    /*----------------------------------------------------------------
    /*  ASIGNAR CUPON USADO
    /*----------------------------------------------------------------*/

    public function assignUsedCoupon()
    {
        //$order_info = $this->getLastClientOrder();
        // $order_info->userID,
        $coupon_id = 133;

        $user_id = 1;

        // Get the current _used_by value for the coupon
        $used_by = get_post_meta($coupon_id, '_used_by', true);

        // Convert the _used_by value into an array of user IDs
        $used_by_array = explode(',', $used_by);

        // Add the new user ID to the _used_by array
        $used_by_array[] = $user_id;

        // Convert the _used_by array back into a comma-separated string
        $new_used_by = implode(',', $used_by_array);

        // Update the _used_by meta data for the coupon with the new value
        update_post_meta($coupon_id, '_used_by', $new_used_by);
    }


}
