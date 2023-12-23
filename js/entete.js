/**
 * Activer le défilement jusqu'en haut de la page, lors d'un clic sur le header
 * (sur les espaces vides de contenus)
 */
function activerDefilementHautPage() {
	document.querySelectorAll("header").forEach(function(element) {
		element.addEventListener("click", function(event) {
			if (event.target !== this) {
				return;
			}

			window.scrollTo(0, 0);
		});
	});
}


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
		let titre = "Revenir en haut de la page";

		// Fermer les min/max
		ouvert = true;
		basculerAffichageMinMax();

		// Header
		const header = document.querySelector("header");
		header.style.paddingBottom = "0px";
		header.classList.add("retractHeader");
		header.style.cursor = "pointer";
		header.onclick = _ => {
			window.scrollTo(0, 0);
		};
		header.title = titre;

		// Box gauche
		document.getElementById("boxGauche").classList.add("retractDiv");
		document.querySelector("#boxGauche > a").classList.add("retractTxt");

		// Box centrale
		const logo = document.querySelector("#boxCentre > img");
		logo.classList.add("retractLogo");
		logo.title = titre;

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
		let titre = '';
	
		// Header
		const header = document.querySelector("header");
		header.style.paddingBottom = paddingHeader;
		header.classList.remove("retractHeader");
		header.style.cursor = "default";
		header.onclick = null;
		header.title = titre;

		// Box gauche
		document.getElementById("boxGauche").classList.remove("retractDiv");
		document.querySelector("#boxGauche > a").classList.remove("retractTxt");

		// Box centrale
		const logo = document.querySelector("#boxCentre > img");
		logo.classList.remove("retractLogo");
		logo.title = titre;

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