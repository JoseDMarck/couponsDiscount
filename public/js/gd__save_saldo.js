class SaldoLocalStorage {
	constructor() {}

	initialize() {
		//localStorage.setItem("ATP_saldo", wp_object.newSaldo);

		// localStorage.setItem("user_new_tp_saldo", wp_object.newSaldo);
		// localStorage.setItem("user_new_tp_saldo", wp_object.newSaldo);
		// localStorage.setItem("user_accumulated_savings", wp_object.newSaldo);

		let disconts = wp_object.couponsApply;

		this.getDiscountFormat(disconts);

		// document.getElementById("gd__atp_saldo").innerHTML = `${generateSaldo}`;
		// document.getElementById("gd__alert").style.display = "block";
	}

	getDiscountFormat(disconts) {
		if (disconts.length === 0) {
			return false;
		}

		let generateSaldo = wp_object.generateSaldo;
		let originalDiscount = disconts;
		let porcentDiscount = [];
		let fixedDiscount = [];
		let porcentDiscountString = "";
		let fixedDiscountNumber = 0;

		//1.- Separamos los descuentos en $ y %
		originalDiscount.map((key) => {
			let discount = key.discount_available;
			if (discount.includes("$")) {
				fixedDiscount.push(discount);
			} else {
				porcentDiscount.push(discount);
			}
		});

		//2.- Separamos el ultimo valor de  para darle sentido a la oración [ '30%', '15%', 'y', '10%' ]
		if (porcentDiscount.length > 1) {
			let porcentDiscountLastValue =
				porcentDiscount[porcentDiscount.length - 1];
			porcentDiscount.pop();
			porcentDiscount.push("y", porcentDiscountLastValue);
		}

		//4.- Convertimos los array de porcentDiscount a texto
		let totalPorcentDiscount = porcentDiscount.length - 3;
		console.log("totalPorcentDiscount", totalPorcentDiscount);
		porcentDiscount.map((key, i) => {
			if (i < totalPorcentDiscount) {
				porcentDiscountString += `${key}, `;
			} else {
				porcentDiscountString += `${key} `;
			}
		});

		//5.- Convertimos los array de porcentDiscount a texto
		let totalFixedDiscount = fixedDiscount.length - 3;
		console.log("totalFixedDiscount", totalFixedDiscount);
		fixedDiscount.map((key, i) => {
			let itemNumber = key.replace("$", "");
			fixedDiscountNumber += parseFloat(itemNumber);
		});

		document.getElementById(
			"gd__coupon_porcent"
		).innerHTML = `${porcentDiscountString}`;

		document.getElementById("gd__atp_saldo").innerHTML = `${generateSaldo}`;
		document.getElementById("gd__alert").style.display = "block";

		if (fixedDiscountNumber > 0) {
			document.getElementById(
				"gd__coupon_fixed"
			).innerHTML = `$${fixedDiscountNumber}`;

			document.getElementById("gd__coupon_fixed_text").innerHTML =
				"más un adicional de:";
		}
		console.log("porcentDiscount", porcentDiscount);
		console.log("fixedDiscount", fixedDiscount);
		console.log("porcentDiscountString", porcentDiscountString);
		console.log("fixedDiscountNumber", fixedDiscountNumber);
	}
}

atp_localStorage = new SaldoLocalStorage();

atp_localStorage.initialize();
