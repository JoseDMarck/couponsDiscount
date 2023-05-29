<?php


/*----------------------------------------------------------------
/* Cuando se activa llama a create_discount_table 
/*----------------------------------------------------------------*/
register_activation_hook(TC__FILE__, 'createCouponsInfoTable');

function createCouponsInfoTable()
{

    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table = 'wp_coupons_data';
    $sql = "CREATE TABLE  $table (
            id BIGINT(20) NOT NULL auto_increment,
            id_user INT(11) NOT NULL,
            user_name VARCHAR(40) NOT NULL,
            last_purchase_mount FLOAT(20) NOT NULL,
            atp_saldo FLOAT(20) NOT NULL,
            atp_current_saldo FLOAT(20) NOT NULL,
            obtained_discount FLOAT(20) NOT NULL DEFAULT 0,
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
