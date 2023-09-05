/**
 * Affiche la jauge représentant la mesure actuelle
 *
 * @param pourcentage de la jauge à remplir
 * @param min de la jauge
 * @param max de la jauge
 * @param unite de la mesure, à concaténer à la valeur
 */
function afficherJauge(pourcentage, min, max, unite) {
	// Permet de recharger la jauge en cas de changement de thème
	let element = document.getElementById("jauge");
	while (element.firstChild) {
		element.removeChild(element.firstChild);
	}

	// Configure la couleur de l'arc en fond de jauge
	let trailColor;
	trailColor = (window.matchMedia("(prefers-color-scheme: light)").matches) ?
		(new CouleursClaires()).gridColor : (new CouleursSombres()).gridColor;

	// Configure la jauge
	let jaugeMesure = new ProgressBar.SemiCircle(jauge, {
		strokeWidth: 6,
		easing: "easeInOut",
		duration: 900,
		color: "url(#gradient)",
		trailColor: trailColor,
		trailWidth: 1,
		step: (_, bar) => {
			var value = ((bar.value() * (max - min)) + min).toFixed(1);
			bar.setText(value + unite);
		}
	});
	jaugeMesure.text.style.fontFamily = "Open Sans";
	jaugeMesure.text.style.fontSize = "25px";
	jaugeMesure.animate(pourcentage);

	// Ajoute le dégradé
	let couleurDonnes, pourcentDegrade, degrade;
	couleurDonnes = (unite === '%') ?
		new CouleursDonneesHum() : new CouleursDonneesTemp();
	pourcentDegrade = couleurDonnes.getPourcentagesDegrade();
	degrade = couleurDonnes.getCouleursDegrade();

	let linearGradient =`<defs>
		<linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%"
			gradientUnits="userSpaceOnUse">
				<stop offset="` + pourcentDegrade[0] + `" stop-color="` +
					degrade[0] + `"/>
				<stop offset="` + pourcentDegrade[1] + `" stop-color="` +
					degrade[1] + `"/>
				<stop offset="` + pourcentDegrade[2] + `" stop-color="` +
					degrade[2] + `"/>
				<stop offset="` + pourcentDegrade[3] + `" stop-color="` +
					degrade[3] + `"/>
		</linearGradient>
		</defs>`

	jaugeMesure.svg.insertAdjacentHTML("afterBegin", linearGradient);
}


/**
 * Fonction permettant de basculer l'affichage d'une div
 *
 * @param identifiantDiv de la div à développer ou réduir
 *
 * @remark "ouvert" est une variable globale permettant de sauvegarder l'état de
 * 			la div, afin de pouvoir effectuer l'action inverse au prochain appel
 */
var ouvert = false;

function basculerAffichage(identifiantDiv) {
	const div = document.getElementById(identifiantDiv);

	if (ouvert) {
		ouvert = false;
		div.classList.remove("developperDiv");
	}
	else {
		ouvert = true;
		div.classList.add("developperDiv");
	}
}


/**
 * Inverse entre afficher ou masquer les valeurs min et max, en développant ou
 * réduisant la div
 */
function basculerAffichageMinMax() {
	basculerAffichage("valeursMinMax");

	// Modifie le titre de la box
	const boxDroite = document.getElementById("boxDroite");
	titre = boxDroite.getAttribute("title");
	tronque = titre.substring(titre.indexOf(" ") + 1);

	let etat = ouvert ? "Masquer " : "Afficher ";
	boxDroite.title = etat + tronque;
}