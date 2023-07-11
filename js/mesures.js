/**
 * Affiche la jauge représentant la mesure actuelle
 *
 * @param pourcentage de la jauge à remplir
 * @param min de la jauge
 * @param max de la jauge
 * @param unite de la mesure, à ajouter à la valeur
 */
function jaugeMesure(pourcentage, min, max, unite) {
	// Permet de recharger la jauge en cas de changement de thème
	let element = document.getElementById("jauge");
	while (element.firstChild) {
		element.removeChild(element.firstChild);
	}

	// Configure la couleur de l'arc en fond de jauge
	let trailcolor;
	if (window.matchMedia("(prefers-color-scheme: light)").matches) {
		trailcolor = (new CouleursClaires()).getGridcolor();
	}
	else {
		trailcolor = (new CouleursSombres()).getGridcolor();
	}

	// Configure la jauge
	let jaugeMesure = new ProgressBar.SemiCircle(jauge, {
		strokeWidth: 6,
		easing: "easeInOut",
		duration: 900,
		color: "url(#gradient)",
		trailColor: trailcolor,
		trailWidth: 1,
		step: (state, bar) => {
			var value = ((bar.value() * (max - min)) + min).toFixed(1);
			bar.setText(value + unite);
		}
	});
	jaugeMesure.text.style.fontFamily = "Open Sans";
	jaugeMesure.text.style.fontSize = "25px";
	jaugeMesure.animate(pourcentage);

	// Ajoute le dégradé
	let couleurDonnes, pourcentDegrade, degrade;
	if (unite === '%') {
		couleurDonnes = new CouleursDonneesHum();
		pourcentDegrade = couleurDonnes.getPourcentagesDegrade();
		degrade = couleurDonnes.getCouleursDegrade();
	}
	else {
		couleurDonnes = new CouleursDonneesTemp();
		pourcentDegrade = couleurDonnes.getPourcentagesDegrade();
		degrade = couleurDonnes.getCouleursDegrade();
	}

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
 * @param identifiantDiv de la div à afficher ou masquer
 */
var ouvert = false;

function basculerAffichage(identifiantDiv) {
	const div = document.getElementById(identifiantDiv);

	if (ouvert) {
		ouvert = false;
		div.style.height = "0px";
		div.style.visibility = "hidden";
	}

	else {
		ouvert = true;
		div.style.visibility = "visible";
		div.style.height = "35px";
	}
}


/**
 * Inverse entre afficher ou masquer les valeurs min et max
 *
 * @param complementTitre à ajouter, en fonction du type de données
 */
function basculerAffichageMinMax(complementTitre) {
	basculerAffichage("valeursMinMax");

	// Modifie le titre de la box
	const boxDroite = document.getElementById("boxDroite");

	if (ouvert) {
		boxDroite.title = "Masquer " + complementTitre;
	}
	else {
		boxDroite.title = "Afficher " + complementTitre;
	}
}