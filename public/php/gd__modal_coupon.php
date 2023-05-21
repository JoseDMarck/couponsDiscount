<div class="gd__alert" id="gd__alert">
    <div class="gd__alert__close" id="gd__alert__close"></div>
    <div class=gd__alert__content>
        <div class="coupon-card"> <img src="<?php echo plugins_url('coupons-discount/public/images/discount_image.png'); ?>" class="logo">
            <h3>¡Enhorabuena!, has ganado: </h3>
            <h3><span id="gd__coupon_porcent"></span></h3>
            <h4><span id="gd__coupon_fixed_text"></span> </h4>
            <h3><span id="gd__coupon_fixed"></span></h3>
            <h3>sobre tu ahorro acumulado</h3>
            <div class="coupon-row">
                <span id="cpnCode">Tienes <span id="gd__atp_saldo">$220</span>
                </span>
                <span id="cpnBtn">de ahorro acumulado</span>
            </div>
            <p>*Puedes usarlo en tu siguiente compra</p>
        </div>
    </div>
</div>
<script>

    const selector = document.querySelector('.gd__alert__content')
    selector.classList.add('magictime', 'vanishIn')

    document.getElementById("gd__alert").addEventListener("click", function (event) {
        document.getElementById("gd__alert").style.display = "none";
    }, false);

</script>
