class SaldoLocalStorage {
	constructor() {}

	saveNewSaldoOnLocalStorage() {
		localStorage.setItem("ATP_saldo", wp_object.newSaldo);

		// localStorage.setItem("user_new_tp_saldo", wp_object.newSaldo);
		// localStorage.setItem("user_new_tp_saldo", wp_object.newSaldo);
		// localStorage.setItem("user_accumulated_savings", wp_object.newSaldo);

		atp_localStorage = new SaldoLocalStorage();
		atp_localStorage.displayBonusMessage();
	}

	displayBonusMessage() {
		console.log(wp_object.userdata);

		let couponType = wp_object.userdata.coupon_type;
		let couponaAcummulated = wp_object.userdata.accumulated_savings;
		let couponValue = wp_object.userdata.coupon_value;

		couponType === "discount_on_last_savings_porcent"
			? (couponValue = couponValue + "%")
			: (couponValue = "$" + couponValue);

		document.getElementById("gd__coupon_porcent").innerHTML = couponValue;
		document.getElementById(
			"gd__atp_saldo"
		).innerHTML = `$${couponaAcummulated}`;
		document.getElementById("gd__alert").style.display = "block";
	}
}

atp_localStorage = new SaldoLocalStorage();

atp_localStorage.saveNewSaldoOnLocalStorage();
