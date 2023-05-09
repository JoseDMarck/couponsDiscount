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
            is_active INT(20) NOT NULL DEFAULT 0,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
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
        );

        $orders = wc_get_orders($args);
        if ($orders):

            $orderFilters = array();
            $i = 0;

            foreach ($orders as $order):
                if ($order->status === $this->STATUS_COMPLETED || $order->status === $this->STATUS_PROCESSING):
                    $orderFilters[$i]["total"] = $order->total;
                    $orderFilters[$i]["customer"] = get_current_user_id();
                    $orderFilters[$i]["status"] = $order->status;
                    $i++;
                endif;
            endforeach;

            $orderInfo = new CouponsDiscount();
            $orderInfo->last_total_order = $orderFilters[0]['total'];
            $orderInfo->user_id = $orderFilters[0]['customer'];

            if ($orderInfo->last_total_order):
                return $orderInfo;
            else:
                return false;
            endif;

        endif;

    }


    /*----------------------------------------------------------------
    /*  Revisar si la orden existe en la base de datos tabka (wp_discount_quantities)
    /*----------------------------------------------------------------*/
    public function checkIsOrderExists()
    {

        $order_info = $this->getLastClientOrder();

        if ($order_info):
            global $wpdb;

            $result = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM wp_discount_quantities WHERE id_user = %s",
                    $order_info->user_id
                )
            );

            return $result;
            // if (!empty($result)):
            //     $this->updateLastClientOrder();
            // else:
            //     $this->insertLastClientOrder();
            // endif;

        endif;

    }

    /*----------------------------------------------------------------
    /*  Actualizar los nuevos datos de la ultima orden en la BD
    /*----------------------------------------------------------------*/
    public function updateLastClientOrder()
    {

        global $wpdb;
        $order_info = $this->getLastClientOrder();
        $wpdb->update(
            'wp_discount_quantities',
            array('last_purchase_mount' => $order_info->last_total_order),
            array('id_user' => $order_info->user_id)
        );

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    }

    /*----------------------------------------------------------------
    /*  Insertar los nuevos datos de la ultima orden en la BD
    /*----------------------------------------------------------------*/
    public function insertLastClientOrder()
    {
        global $wpdb;
        $order_info = $this->getLastClientOrder();
        $wpdb->insert(
            'wp_discount_quantities',
            array(
                'last_purchase_mount' => $order_info->last_total_order,
                'id_user' => $order_info->user_id,
            )
        );
    }
}
