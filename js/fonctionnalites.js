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

class dimensions {
	constructor(
		logoW, logoH,
		logoWR, logoHR,
		icone, iconeR
	) {
		this.logoW = logoW;
		this.logoH = logoH;
		this.logoWR = logoWR;
		this.logoHR = logoHR;
		this.icone = icone;
		this.iconeR = iconeR;
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
}

class dimensionsBureau extends dimensions {
	constructor() {
		super(
			"390px", "98px",
			"239px", "60px",
			"64px", "42px"
		);
	}
}

class dimensionsMobile extends dimensions {
	constructor() {
		super(
			"320px", "80px",
			"200px", "50px",
			"0px", "44px"
		);
	}
}

/**
 * Rétracte le header au scroll
 */
function retracterScroll() {
	let dimensions;
	if (window.matchMedia("(max-width: 769px)").matches) {
		dimensions = new dimensionsMobile();
	}
	else {
		dimensions = new dimensionsBureau();
	}

	if (document.documentElement.scrollTop > 38) {
		// Logo
		document.querySelector("#boxCentre > img").style.width = dimensions.getLogoWR();
		document.querySelector("#boxCentre > img").style.height = dimensions.getLogoHR();


		// Images min et max
		document.querySelector("#boxDroite > div > img:first-child").style.width = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:first-child").style.height = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:first-child").style.opacity = 0;

		document.querySelector("#boxDroite > div > img:last-child").style.width = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:last-child").style.height = dimensions.getIconeR();
		document.querySelector("#boxDroite > div > img:last-child").style.opacity = 0;

		document.getElementById("boxDroite").style.visibility = "hidden";

		// Lien vers l'autre page
		document.querySelector("#boxGauche > a").style.opacity = 0;
		document.querySelector("#boxGauche > a").style.visibility = "hidden";
	}
	else {
		// Logo
		document.querySelector("#boxCentre > img").style.width = dimensions.getLogoW();
		document.querySelector("#boxCentre > img").style.height = dimensions.getLogoH();

		// Images min et max
		document.getElementById("boxDroite").style.visibility = "visible";

		document.querySelector("#boxDroite > div > img:first-child").style.width = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:first-child").style.height = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:first-child").style.opacity = 1;

		document.querySelector("#boxDroite > div > img:last-child").style.width = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:last-child").style.height = dimensions.getIcone();
		document.querySelector("#boxDroite > div > img:last-child").style.opacity = 1;

		// Lien vers l'autre page
		document.querySelector("#boxGauche > a").style.opacity = 1;
		document.querySelector("#boxGauche > a").style.visibility = "visible";
	}
}

// function retracterScroll() {
// 	if (document.documentElement.scrollTop > 83) {
// 		// Logo
// 		document.querySelector("#boxCentre > img").style.width = "200px";
// 		document.querySelector("#boxCentre > img").style.height = "50px";


// 		// Images min et max
// 		document.querySelector("#boxDroite").style.height = "0px";

// 		document.querySelector("#boxDroite > div > img:first-child").style.height = "0px";
// 		document.querySelector("#boxDroite > div > img:first-child").style.opacity = 0;

// 		document.querySelector("#boxDroite > div > img:last-child").style.height = "0px";
// 		document.querySelector("#boxDroite > div > img:last-child").style.opacity = 0;

// 		// Lien vers l'autre page
// 		document.querySelector("#boxGauche").style.height = "0px";
// 		document.querySelector("#boxGauche").style.visibility = "hidden";
// 		document.querySelector("#boxGauche > a").style.opacity = 0;
// 	}
// 	else {
// 		// Logo
// 		document.querySelector("#boxCentre > img").style.width = "320px";
// 		document.querySelector("#boxCentre > img").style.height = "80px";

// 		// Images min et max
// 		document.querySelector("#boxDroite").style.height = "79px";

// 		document.querySelector("#boxDroite > div > img:first-child").style.height = "44px";
// 		document.querySelector("#boxDroite > div > img:first-child").style.opacity = 1;

// 		document.querySelector("#boxDroite > div > img:last-child").style.height = "44px";
// 		document.querySelector("#boxDroite > div > img:last-child").style.opacity = 1;

// 		// Lien vers l'autre page
// 		document.querySelector("#boxGauche").style.visibility = "visible";
// 		document.querySelector("#boxGauche").style.height = "79px";
// 		document.querySelector("#boxGauche > a ").style.opacity = 1;

// 	}
// }