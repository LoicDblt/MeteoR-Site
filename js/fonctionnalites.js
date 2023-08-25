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


/**
 * Classe pour les dimensions du header
 */
class dimensions {
	constructor(
		logoW, logoH,
		logoWR, logoHR,
		icone, iconeR,
		headerH, headerHR,
		scrollH
	) {
		this.logoW = logoW;
		this.logoH = logoH;
		this.logoWR = logoWR;
		this.logoHR = logoHR;
		this.icone = icone;
		this.iconeR = iconeR;
		this.headerH = headerH;
		this.headerHR = headerHR;
		this.scrollH = scrollH;
	}

	getLogoW() {
		return this.logoW;
	}
	getLogoH() {
		return this.logoH;
	}
	getLogoWR() {
		return this.logoWR;
	}
	getLogoHR() {
		return this.logoHR;
	}
	getIcone() {
		return this.icone;
	}
	getIconeR() {
		return this.iconeR;
	}
	getHeaderH() {
		return this.headerH;
	}
	getHeaderHR() {
		return this.headerHR;
	}
	getScrollH() {
		return this.scrollH;
	}
}

class dimensionsBureau extends dimensions {
	constructor() {
		super(
			"390px", "98px",
			"239px", "60px",
			"64px", "42px",
			"unset", "unset",
			38
		);
	}
}

class dimensionsMobile extends dimensions {
	constructor() {
		super(
			"320px", "80px",
			"200px", "50px",
			"44px", "0px",
			"79px", "0px",
			83
		);
	}
}


/**
 * Active la gestion du header réduit en fonction de la distance de défilement
 */
function activerHeaderReduit() {
	window.onload = function() {retracterScroll()};
	window.onscroll = function() {retracterScroll()};
}


/**
 * Rétracte le header au scroll
 */
function retracterScroll() {
	// Fermer les min/max
	ouvert = true;
	basculerAffichage("valeursMinMax");

	// Récupère les dimensions en fonction de la taille de l'écran
	let dimensions;
	if (window.matchMedia("(max-width: 769px)").matches) {
		dimensions = new dimensionsMobile();
	}
	else {
		dimensions = new dimensionsBureau();
	}

	// Réduit le header en fonction de la distance de défilement
	if (document.documentElement.scrollTop > dimensions.getScrollH()) {
		// Logo
		document.querySelector("#boxCentre > img").style.width =
			dimensions.getLogoWR();
		document.querySelector("#boxCentre > img").style.height =
			dimensions.getLogoHR();

		// Images min et max
		document.querySelector("#boxDroite").style.height =
			dimensions.getHeaderHR();

		document.querySelector("#boxDroite > div > img:first-child")
			.style.width = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:first-child")
			.style.height = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:first-child")
			.style.opacity = 0;

		document.querySelector("#boxDroite > div > img:last-child")
			.style.width = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:last-child")
			.style.height = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:last-child")
			.style.opacity = 0;

		document.getElementById("boxDroite").style.visibility = "hidden";

		// Lien vers l'autre page
		document.querySelector("#boxGauche").style.height =
			dimensions.getHeaderHR();
		document.querySelector("#boxGauche").style.visibility = "hidden";
		document.querySelector("#boxGauche > a").style.opacity = 0;
	}
	else {
		// Logo
		document.querySelector("#boxCentre > img").style.width =
			dimensions.getLogoW();
		document.querySelector("#boxCentre > img").style.height =
			dimensions.getLogoH();

		// Images min et max
		document.querySelector("#boxDroite").style.height =
			dimensions.getHeaderH();

		document.getElementById("boxDroite").style.visibility = "visible";

		document.querySelector("#boxDroite > div > img:first-child")
			.style.width = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:first-child")
			.style.height = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:first-child")
			.style.opacity = 1;

		document.querySelector("#boxDroite > div > img:last-child")
			.style.width = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:last-child")
			.style.height = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:last-child")
			.style.opacity = 1;

		// Lien vers l'autre page
		document.querySelector("#boxGauche").style.visibility = "visible";
		document.querySelector("#boxGauche").style.height =
			dimensions.getHeaderH();
		document.querySelector("#boxGauche > a").style.opacity = 1;
	}
}