class ATPSaldo {
	ATP_saldo;

	constructor() {
		this.ATP_saldo = localStorage.getItem("ATP_saldo");
	}

	getDataFromLocalStorage() {
		console.log("ATP_saldo", this.ATP_saldo);
	}

	sendSaldoValue() {
		jQuery.ajax({
			url: wp_object.ajax_url,
			data: {
				action: "set_atp_saldo",
				saldo: this.ATP_saldo,
			},
			success: function (response) {
				console.log("Saldo saved");
			},
			error: function (error) {
				console.log("error", error);
			},
		});
	}
}

atp_saldo = new ATPSaldo();
atp_saldo.getDataFromLocalStorage();
atp_saldo.sendSaldoValue();
