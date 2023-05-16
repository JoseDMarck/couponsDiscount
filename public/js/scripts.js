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
			url: ajax_object.ajax_url,
			data: {
				action: "save_client_on_db",
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
