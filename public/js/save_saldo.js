class SaldoLocalStorage {
	constructor() {}

	saveNewSaldoOnLocalStorage() {
		localStorage.setItem("ATP_saldo", wp_object.newSaldo);
		atp_localStorage = new SaldoLocalStorage();
		atp_localStorage.displayBonusMessage();
	}

	displayBonusMessage() {
		document.getElementById("gd__alert").style.display = "block";
	}
}

atp_localStorage = new SaldoLocalStorage();

atp_localStorage.saveNewSaldoOnLocalStorage();
