/**
 * Active la gestion du header réduit en fonction de la distance de défilement
 */
function activerHeaderReduit() {
	// Récupère les dimensions en fonction de la taille de l'écran
	if (window.matchMedia("(max-width: 769px)").matches) {
		dimensions = new dimensionsMobile();
	}
	else {
		dimensions = new dimensionsBureau();
	}

	window.onload = function() {retracterScroll()};
	window.onscroll = function() {retracterScroll()};
}


/**
 * Rétracte le header en fonction de la distance de défilement
 */
function retracterScroll() {
	if (document.documentElement.scrollTop > dimensions.scrollH) {
		// Fermer les min/max
		ouvert = true;
		basculerAffichage("valeursMinMax");

		// Header
		document.querySelector("header").style.paddingBottom = "0px";

		// Box centrale
		document.querySelector("#boxCentre > img").style.width =
			dimensions.logoWR;
		document.querySelector("#boxCentre > img").style.height =
			dimensions.logoHR;

		// Box droite
		document.getElementById("valeursMinMax").style.transitionDuration =
			"0.2s";
		document.getElementById("boxDroite").style.height = dimensions.headerHR;

		document.querySelector("#boxDroite > div > img:first-child")
			.style.width = dimensions.iconeR;
		document.querySelector("#boxDroite > div > img:first-child")
			.style.height = dimensions.iconeR;
		document.querySelector("#boxDroite > div > img:first-child")
			.style.opacity = 0;

		document.querySelector("#boxDroite > div > img:last-child")
			.style.width = dimensions.iconeR;
		document.querySelector("#boxDroite > div > img:last-child")
			.style.height = dimensions.iconeR;
		document.querySelector("#boxDroite > div > img:last-child")
			.style.opacity = 0;

		document.getElementById("boxDroite").style.visibility = "hidden";

		// Box gauche
		document.getElementById("boxGauche").style.height = dimensions.headerHR;
		document.getElementById("boxGauche").style.visibility = "hidden";
		document.querySelector("#boxGauche > a").style.opacity = 0;
	}
	else {
		// Header
		document.querySelector("header").style.paddingBottom =
			dimensions.paddingB;

		// Box centrale
		document.querySelector("#boxCentre > img").style.width =
			dimensions.logoW;
		document.querySelector("#boxCentre > img").style.height =
			dimensions.logoH;

		// Box droite
		document.getElementById("valeursMinMax").style.transitionDuration =
			"0.4s";
		document.getElementById("boxDroite").style.visibility = "visible";
		document.querySelector("#boxDroite").style.height = dimensions.headerH;

		document.querySelector("#boxDroite > div > img:first-child")
			.style.width = dimensions.icone;
		document.querySelector("#boxDroite > div > img:first-child")
			.style.height = dimensions.icone;
		document.querySelector("#boxDroite > div > img:first-child")
			.style.opacity = 1;

		document.querySelector("#boxDroite > div > img:last-child")
			.style.width = dimensions.icone;
		document.querySelector("#boxDroite > div > img:last-child")
			.style.height = dimensions.icone;
		document.querySelector("#boxDroite > div > img:last-child")
			.style.opacity = 1;

		// Box gauche
		document.getElementById("boxGauche").style.visibility = "visible";
		document.getElementById("boxGauche").style.height = dimensions.headerH;
		document.querySelector("#boxGauche > a").style.opacity = 1;
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