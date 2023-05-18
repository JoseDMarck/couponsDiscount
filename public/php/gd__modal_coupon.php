<div class="gd__alert" id="gd__alert">
    <div class="gd__alert__close" id="gd__alert__close"></div>
    <div class=gd__alert__content>
        <div class="coupon-card"> <img src="<?php echo plugins_url('coupons-discount/public/images/discount_image.png'); ?>" class="logo">
            <h3>¡Enhorabuena, has ganado <span id="gd__coupon_porcent">10%</span> sobre <br>tu ahorro acumulado! </h3>
            <di class="coupon-row"> <span id="cpnCode">Tienes <span id="gd__atp_saldo">$220</span> </span> <span id="cpnBtn">De ahorro Acumulado</span> </di>
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
