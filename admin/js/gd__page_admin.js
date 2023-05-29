jQuery(document).ready(function ($) {
	const gd__button__truncated = document.getElementById(
		"gd__button__truncated"
	);

	gd__button__truncated.addEventListener("click", (event) => {
		console.log("gd__button__truncated");
		jQuery.ajax({
			url: wp_object.ajax_url,
			data: {
				action: "truncate_coupons_data",
			},
			success: function (response) {
				console.log("Truncated Table");

				document.getElementById(
					"gd__alert--truncate"
				).style.display = "block";

				const selector = document.querySelector(
					".gd__alert__content"
				);
				selector.classList.add("magictime", "tinRightIn");

				document
					.getElementById("gd__alert--truncate")
					.addEventListener(
						"click",
						function (event) {
							document.getElementById(
								"gd__alert--truncate"
							).style.display = "none";
						},
						false
					);

				setTimeout(() => {
					location.reload();
				}, 3000);
			},
			error: function (error) {
				console.log("error", error);
			},
		});
	});

	function truncateCouponsTable() {}
});
