/**
 * Active le service worker, sauf sur Firefox bureau, car soucis de performances
 */
function lancerServiceWorker() {
	if (
		"serviceWorker" in navigator &&
		(window.navigator.userAgent.toLowerCase().indexOf("firefox") === -1 ||
		window.navigator.userAgent.toLowerCase().indexOf("mobile") > -1)
	) {
		navigator.serviceWorker.register("../service_worker.js")
		.then({})
		.catch(err => {
			console.log("lancerServiceWorker - Enregistrement échoué :", err);
		})
	}
}