<?php

class CouponsDiscount
{

    public $lastTotalOrder;
    public $userID;
    public $accumulatedSavings = 0;
    public $isCouponUsed;
    public $couponCode;
    public $couponsData = array();
    public $userData = array();
    public $STATUS_COMPLETED = 'completed';
    public $STATUS_PROCESSING = 'processing';

    public $TESTVAR = 'testing';

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
            id_user INT(11) NOT NULL,
            last_purchase_mount FLOAT(20) NOT NULL,
            tp_saldo FLOAT(20) NOT NULL,
            accumulated_savings FLOAT(20) NOT NULL DEFAULT 0,
            is_coupon_used INT(20) NOT NULL DEFAULT 0,
            coupon_code VARCHAR(40) NOT NULL,
            coupon_value VARCHAR(40) NOT NULL DEFAULT 0,
            coupon_type VARCHAR(40) NOT NULL,
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
    /*  Guardamamos en base de datos el ahorro acumulado
    /*----------------------------------------------------------------*/
    public function saveClientSaldoOnDB($saldo)
    {

        global $wpdb;
        $wpdb->update(
            'wp_discount_quantities',
            array(

                'tp_saldo' => $saldo,
            ),
            array('id_user' => get_current_user_id())
        );


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
                get_current_user_id()
            )
        );

        //print_r($result);

        if (!empty($result)):
            return true;
        endif;
    }

    /*----------------------------------------------------------------
    /*  Obtener informacion de cupones
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
    /*  settear los datos del cupon
    /*----------------------------------------------------------------*/
    public function setCouponData($coupon)
    {
        $this->couponsData = $coupon;
    }

    /*----------------------------------------------------------------
    /*  Obtener la informacion del usuario guardada en BD
    /*----------------------------------------------------------------*/

    public function setUserData()
    {
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM wp_discount_quantities WHERE id_user = %s",
                get_current_user_id()
            )
        );

        /*todo: Asignar la comporovacion con el id del cupon porque el 
        usuario puede tener mas registros pero diferentes cupones
        */

        $this->userData = $result[0];
    }


    /*----------------------------------------------------------------
    /*  Revisamos si el cupon ha sido usado 
    /*----------------------------------------------------------------*/

    public function checkIsCouponUsed()
    {



        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare(
                'SELECT * FROM wp_discount_quantities
                WHERE id_user = %s AND is_coupon_used = 1 AND coupon_code = %s',
                get_current_user_id(),
                $this->userData->coupon_code
            )
        );

        /*todo: Asignar la comporovacion con el id del cupon porque el 
        usuario puede tener mas registros pero diferentes cupones
        */

        if (!empty($result)):
            return true;
        endif;

        return false;
    }



    /*----------------------------------------------------------------
    /*  Actualizar los nuevos datos de la ultima orden en la BD
    /*----------------------------------------------------------------*/
    public function updateLastClientOrder()
    {

        // echo "<h1>updateLastClientOrder()</h1>";

        // print_r($this->userData);
        // echo "<br>";
        // echo $this->lastTotalOrder;
        // echo "<br>";
        // echo $this->userData->accumulated_savings;
        // echo "<br>";
        // echo $this->userData->is_coupon_used;
        // echo "<br>";
        // echo $this->userData->coupon_code;
        // echo "<br>";
        // echo get_current_user_id();


        global $wpdb;
        $wpdb->update(
            'wp_discount_quantities',
            array(
                'last_purchase_mount' => $this->lastTotalOrder,
                'accumulated_savings' => $this->userData->accumulated_savings,
                'is_coupon_used' => $this->userData->is_coupon_used,
                'coupon_code' => $this->userData->coupon_code,
                'coupon_value' => $this->userData->coupon_value,
                'coupon_type' => $this->userData->coupon_type,

            ),
            array('id_user' => get_current_user_id())
        );

        return $this->userData;
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
                'id_user' => get_current_user_id(),
            )
        );
    }

    /*----------------------------------------------------------------
    /*  OBTENER LA INFORMACION DE CUPONES
    /*----------------------------------------------------------------*/

    public function getCouponDiscount()
    {

        $couponData = $this->couponsData;
        $minimumCouponAmount = $couponData[0]['minimum_amount']; //  $400
        $discountAvailable = $couponData[0]['amount']; // 10% minimun
        $couponCode = $couponData[0]['name']; // 10% minimun
        $tp_saldo = $this->userData->tp_saldo; // $200
        $coupon_type = $couponData[0]['type'];
        $accumulatedSavings = $this->userData->accumulated_savings + $tp_saldo; // $200


        //Si el último pedido es mayor al monto minimo permitido en el cupon 
        if ($this->lastTotalOrder < $minimumCouponAmount):
            return false;
        endif;

        // Si no tiene ahorro acumulado termina el proceso
        if ($tp_saldo === 0):
            return false;
        endif;

        //Operaciones: Aplicar 10% (discountAvailable) sobre mi ultimo ahorro (accumulatedSavings)
        $obtainedDiscount = ($discountAvailable / 100) * $accumulatedSavings; //(10/100)*200

        //Sumar el descuento obtenido a mi ahorro acumulado 
        $discountApply = $obtainedDiscount + $accumulatedSavings;


        //Actualizar objeto de user 
        $this->userData->accumulated_savings = $discountApply;
        $this->userData->is_coupon_used = 1;
        $this->userData->coupon_code = $couponCode;
        $this->userData->coupon_value = $discountAvailable;
        $this->userData->coupon_type = $coupon_type;



        return true;
    }

}
