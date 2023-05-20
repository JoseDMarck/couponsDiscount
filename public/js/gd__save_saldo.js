class SaldoLocalStorage {
	constructor() {}

	initialize() {
		//localStorage.setItem("ATP_saldo", wp_object.newSaldo);

		// localStorage.setItem("user_new_tp_saldo", wp_object.newSaldo);
		// localStorage.setItem("user_new_tp_saldo", wp_object.newSaldo);
		// localStorage.setItem("user_accumulated_savings", wp_object.newSaldo);

		console.log(wp_object.couponsApply);

		let generateSaldo = wp_object.generateSaldo;
		let couponsApply = wp_object.couponsApply;
		let formatDiscount = [];

		couponsApply.map((key) => {
			console.log(key.discount_available);
			formatDiscount.push(key.discount_available);
			//document.getElementById("gd__atp_saldo").innerHTML = key.discount_available
		});

		document.getElementById("gd__coupon_porcent").innerHTML =
			formatDiscount.join(" ");

		document.getElementById("gd__atp_saldo").innerHTML = `${generateSaldo}`;
		document.getElementById("gd__alert").style.display = "block";
	}
}

atp_localStorage = new SaldoLocalStorage();

atp_localStorage.initialize();
