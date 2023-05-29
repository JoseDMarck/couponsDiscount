<?php
global $wpdb;
$discounts = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM wp_coupons_data")
);


$discountsATP = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM wp_usermeta WHERE  meta_key = %s",
        "atp_saldo",
    )
);
?>
<main class="gd__adminPage">
    <button class="gd__button gd__button--truncated"> Limpiar datos </button>
    <h2>Listado de cupones y usuarios:</h2>
    <div class="gd__content gd__content--no-border-bottom gd__green gd__text-white">
        <div class="gd__content__title"> Usuario: </div>
        <div class="gd__content__title"> Última Compra: </div>
        <div class="gd__content__title"> ATP Saldo: </div>
        <div class="gd__content__title"> ATP Saldo Actual: </div>
        <div class="gd__content__title"> Descuento: </div>
        <div class="gd__content__title"> Ahorro acumulado: </div>
        <div class="gd__content__title"> Cupón Estatus: </div>
        <div class="gd__content__title"> Cupón código: </div>
        <div class="gd__content__title"> Cupón valor: </div>
        <div class="gd__content__title"> Cupón Tipo: </div>
    </div>
    <?php
    foreach ($discounts as $discount): ?>
        <div class="gd__content">
            <div class="gd__content__title gd__content__title--height"> ID:
                <?php echo $discount->id_user; ?> -
                <?php echo $discount->user_name; ?>
            </div>
            <div class="gd__content__title gd__content__title--height"> $
                <?php echo $discount->last_purchase_mount; ?>
            </div>
            <div class="gd__content__title gd__content__title--height"> $
                <?php echo $discount->atp_saldo; ?>
            </div>
            <div class="gd__content__title gd__content__title--height">$
                <?php echo $discount->atp_current_saldo; ?>
            </div>
            <div class="gd__content__title gd__content__title--height"> $
                <?php echo $discount->obtained_discount; ?>
            </div>
            <div class="gd__content__title gd__content__title--height"> $
                <?php echo $discount->accumulated_savings; ?>
            </div>
            <div class="gd__content__title gd__content__title--height">
                <?php
                if ($discount->is_coupon_used === "0"): ?>
                    <span class="gd__status--active">Sin usar</span>
                <?php else: ?>
                    <span class="gd__status--used">Usado</span>
                <?php endif ?>
            </div>
            <div class="gd__content__title gd__content__title--height">
                <?php echo $discount->coupon_code; ?>
            </div>
            <div class="gd__content__title gd__content__title--height">
                <?php echo $discount->coupon_value; ?>
            </div>
            <div class="gd__content__title gd__content__title--height">
                <?php
                if ($discount->coupon_type === "discount_on_last_savings_fixed"): ?> fijo $
                <?php else: ?> Porcentaje %
                <?php endif ?>
            </div>
        </div>
        <?php
    endforeach;
    ?>
</main>
<main class="gd__adminPage">
    <h2>ATP saldo por usuario:</h2>
    <div class="gd__content gd__content--small  gd__content--no-border-bottom gd__green gd__text-white">
        <div class="gd__content__title"> Usuario: </div>
        <div class="gd__content__title"> ATP Saldo: </div>
    </div>
    <?php
    foreach ($discountsATP as $discount): ?>
        <div class="gd__content gd__content--small">
            <div class="gd__content__title gd__content__title--height">
                <?php echo $discount->user_id; ?>
            </div>
            <div class="gd__content__title gd__content__title--height"> $
                <?php echo $discount->meta_value; ?>
            </div>
        </div>
        <?php
    endforeach;
    ?>
</main>
