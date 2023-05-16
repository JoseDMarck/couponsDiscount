class SaldoLocalStorage {
	constructor() {}

	saveNewSaldo() {
		localStorage.setItem("ATP_saldo", wp_object.newSaldo);
		alert("Se ha bonificado tu saldo...");
	}
}

atp_localStorage = new SaldoLocalStorage();

atp_localStorage.saveNewSaldo();
