<?php
class CouponsDiscount
{

    public function create_discount_table()
    {
        global $wpdb;
        $table_name = 'wp_discount_quantities';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) NOT NULL auto_increment,
            last_purchase_mount FLOAT(20) NOT NULL,
            id_user int(11) NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($sql);
    }
}
