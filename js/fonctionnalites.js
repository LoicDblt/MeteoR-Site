/**
 * Active la gestion du header réduit en fonction de la distance de défilement
 */
function activerHeaderReduit() {
	// Détermine la distance de défilement avant rétractation du header
	if (window.matchMedia("(max-width: 769px)").matches) {
		distanceScroll = 120;
		paddingHeader = "10px";
	}
	else {
		distanceScroll = 35;
		paddingHeader = "0px";
	}

	window.onload = function() {retracterScroll()};
	window.onscroll = function() {retracterScroll()};
}


/**
 * Rétracte le header en fonction de la distance de défilement
 */
function retracterScroll() {
	if (document.documentElement.scrollTop > distanceScroll) {
		// Fermer les min/max
		ouvert = true;
		basculerAffichageMinMax();

		// Header
		document.querySelector("header").style.paddingBottom = "0px";
		document.querySelector("header").classList.add("retractHeader");

		// Box gauche
		document.getElementById("boxGauche").classList.add("retractDiv");
		document.querySelector("#boxGauche > a").classList.add("retractTxt");

		// Box centrale
		document.querySelector("#boxCentre > img").classList.add("retractLogo");

		// Box droite
		document.getElementById("boxDroite").classList.add("retractDiv");
		document.querySelector("#boxDroite > div > img:first-child").classList
			.add("retractImg");
		document.querySelector("#boxDroite > div > img:last-child").classList
			.add("retractImg");
		document.querySelector("#valeursMinMax > p:first-child").classList
			.add("retractTxt");
		document.querySelector("#valeursMinMax > p:last-child").classList
			.add("retractTxt");
	}
	else {
		// Header
		document.querySelector("header").style.paddingBottom =
		paddingHeader;
		document.querySelector("header").classList.remove("retractHeader");

		// Box gauche
		document.getElementById("boxGauche").classList.remove("retractDiv");
		document.querySelector("#boxGauche > a").classList.remove("retractTxt");

		// Box centrale
		document.querySelector("#boxCentre > img").classList
			.remove("retractLogo");

		// Box droite
		document.getElementById("boxDroite").classList.remove("retractDiv");
		document.querySelector("#boxDroite > div > img:first-child").classList
			.remove("retractImg");
		document.querySelector("#boxDroite > div > img:last-child").classList
			.remove("retractImg");
		document.querySelector("#valeursMinMax > p:first-child").classList
			.remove("retractTxt");
		document.querySelector("#valeursMinMax > p:last-child").classList
			.remove("retractTxt");
	}
}


/**
 * Active le service worker, sauf sur Firefox bureau, car soucis de performances
 */
function activerServiceWorker() {
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