jQuery(document).ready(function ($) {
	const gd__truntButtom = document.getElementById("gd__button__truncated");
	const gd__truntAlert = document.getElementById("gd__alert_truncate");

	if (gd__truntButtom)
		gd__truntButtom.addEventListener("click", (event) => {
			jQuery.ajax({
				url: wp_object.ajax_url,
				data: {
					action: "truncate_coupons_data",
				},
				success: function (response) {
					displayMessage();
				},
				error: function (error) {
					console.log("error", error);
				},
			});
		});

	function displayMessage() {
		gd__truntAlert.style.display = "block";

		setTimeout(() => {
			location.reload();
		}, 2000);
	}
});
