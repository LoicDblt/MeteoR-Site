// Classe pour les couleurs du graphique
class Couleurs{
	constructor(bgcolor, gridcolor, color){
		this.bgcolor = bgcolor;
		this.gridcolor = gridcolor;
		this.color = color;
	}

	getBgColor(){
		return this.bgcolor;
	}
	getGridColor(){
		return this.gridcolor;
	}
	getColor(){
		return this.color;
	}
	getTableauToutesCouleurs(){
		return Array(this.getBgColor(), this.getGridColor(), this.getColor());
	}
}

class CouleursClaires extends Couleurs{
	constructor(){
		super("#ffffff", "#eeeeee", "#404040");
	}
}

class CouleursSombres extends Couleurs{
	constructor(){
		super("#000000", "#4a484c", "#bfbfbf");
	}
}


/**
 * Récupère les données dans la base de données
 * @param nomColonne des données à récupérer
 * @returns les données, ou l'erreur rencontrée
 */
function recupBdd(nomColonne){
	return new Promise((resolve, reject) => {
		// Champ à envoyer au back (pour connaître la colonne à récupérer)
		let champPost = new FormData();
		champPost.append("nomColonne", nomColonne);

		// Récupère les dates des mesures et les données de la colonne demandée
		fetch("../classes/recupColonnes.php", {
			method: "POST",
			body: champPost
		})
		.then(reponse => {
			reponse.json()
				.then(donnees => {
					resolve(donnees);
				})
				.catch(erreur => {
					reject(erreur);
				})
		})
		.catch(erreur => {
			reject(erreur);
		})
	})
}


/**
 * Récupère les données de d'absisses et d'ordonnées pour le graphique
 * @param nomColonne des données à récupérer
 * @returns un tableau avec les données d'abscisses et d'ordonnées
 */
function recupAbsOrd(nomColonne) {
	return new Promise(resolve => {
		recupBdd(nomColonne)
		.then(retour => {
			resolve(retour);
		})
		.catch(erreur => {
			console.log(erreur);
		})
	})
}


/**
 * Paramètre le graphique, récupère les données et affiche le tout
 * @param nomColonne des données à récupérer
 * @param typeDonnees à afficher dans les labels
 * @param unite à afficher sur l'axe des ordonnées
 */
function parametrerAfficherGraphique(nomColonne, typeDonnees, unite) {
	// Configure le graphique
	const config = {
		locale: "fr",
		displayModeBar: false,
		responsive: true,
		showAxisDragHandles: false
	}

	// Configure le placement
	let top, right, bottom, left, nticks;
	if (window.matchMedia && window.matchMedia("(max-width: 769px)").matches){
		top = 0;
		right = 5;
		bottom = 35;
		left = 40;
		nticks = 5;
	}
	else{
		top = 10;
		right = 10;
		bottom = 50;
		left = 55;
		nticks = 8;
	}

	// Configure les couleurs
	let couleurs, bgcolor, gridcolor, color;
	if (window.matchMedia && window.matchMedia("(prefers-color-scheme: light)")
	.matches){
		couleurs = new CouleursClaires();
		[bgcolor, gridcolor, color] = couleurs.getTableauToutesCouleurs();
	}
	else{
		couleurs = new CouleursSombres();
		[bgcolor, gridcolor, color] = couleurs.getTableauToutesCouleurs();
	}

	// Configure le style
	const layout = {
		showlegend: false,
		separators: ".,",
		margin: {
			t: top,
			r: right,
			b: bottom,
			l: left,
			pad: 4
		},
		font: {
			family: "Open Sans",
			color: color
		},
		plot_bgcolor: bgcolor,
		paper_bgcolor: bgcolor,
		xaxis: {
			showgrid: false,
			nticks: nticks,
			tickformatstops: [
				{
					"dtickrange": [null, 60000],
					"value": "%-d %B<br>%Hh%M.%S"
				},
				{
					"dtickrange": [60000, 3600000],
					"value": "%-d %B<br>%Hh%M"
				},
				{
					"dtickrange": [3600000, 86400000],
					"value": "%-d %B<br>%Hh%M"
				},
				{
					"dtickrange": [86400000, null],
					"value": "%-d %B"
				}
			]
		},
		yaxis: {
			gridcolor: gridcolor,
			gridcolorwidth: 1,
			fixedrange: true,
			tickformat: ".1f",
			ticksuffix: (unite === "%") ? unite + " " : unite
		}
	}

	// Récupère les données et affiche le graphique
	recupAbsOrd(nomColonne)
	.then(retour => {
		const data = [{
			x: JSON.parse(retour[0]),
			y: JSON.parse(retour[1]),
			type: "scatter",
			connectgaps: true,
			line: {
				color: "#32a6f5",
				width: 2,
				shape: "spline"
			},
			hovertemplate: "<b>" + typeDonnees + " :</b> %{y:.1f}" + unite +
							"<br><b>Date :</b> %{x|%a %-d %B à %Hh%M}" +
							"<extra></extra>",
			hoverlabel: {
				align: "left",
				bordercolor: "#32a6f5",
				font: {
					family: "Open Sans",
					color: "#ffffff"
				}
			},
			showlegend: false
		}]

		Plotly.newPlot("graphique", data, layout, config);
	})
}


/**
 * Inverse entre afficher ou masquer les valeurs min et max
 * @param nouveauTitre à adapter en fonction de l'affichage
 */
function inverserAffichageMinMax(nouveauTitre){
	const titre = document.getElementById("boxDroite");
	const divMinMax = document.getElementById("valeursMinMax");
	$(divMinMax).slideToggle(400, () => {
		if ($(divMinMax).css("display") === "flex")
			titre.title = "Masquer " + nouveauTitre;
		else
			titre.title = "Afficher " + nouveauTitre;
	}).css("display", "flex");
}


/**
 * Active le service worker, sauf sur Firefox bureau
 */
function lancerServiceWorker(){
	if (
		"serviceWorker" in navigator &&
		(window.navigator.userAgent.toLowerCase().indexOf("firefox") === -1 ||
		window.navigator.userAgent.toLowerCase().indexOf("mobile") > -1)
	){
		navigator.serviceWorker.register("../service_worker.js")
		.then({})
		.catch(function(erreur){
			console.log("Service worker - enregistrement echoué :", erreur);
		})
	}
}

/**
 * Affiche la barre de progression pour la mesure actuelle
 * @param pourcentage de la barre à afficher
 * @param min de la barre
 * @param max de la barre
 * @param unite de la mesure à ajouter à la valeur
 */
function barreProgression(pourcentage, min, max, unite){
	// Configure la barre de progression
	var bar = new ProgressBar.SemiCircle(container, {
		strokeWidth: 6,
		easing: "easeInOut",
		duration: 900,
		color: "url(#gradient)",
		trailColor: "#eeeeee",
		trailWidth: 1,
		svgStyle: null,
		step: (state, bar) => {
			var value = ((bar.value() * (max - min)) + min).toFixed(1);
			bar.setText(value + unite);
		}
	});
	bar.text.style.fontFamily = "Open Sans";
	bar.text.style.fontSize = "25px";
	bar.animate(pourcentage);

	// Ajoute le dégradé
	let linearGradient =`<defs>
		<linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%"
			gradientUnits="userSpaceOnUse">
				<stop offset="0%" stop-color="#0074ff"/>
				<stop offset="35%" stop-color="#00e600"/>
				<stop offset="50%" stop-color="#b0e300"/>
				<stop offset="65%" stop-color="#d1e600"/>
				<stop offset="100%" stop-color="#fb4d0f"/>
		</linearGradient>
		</defs>`

	if (unite === '%') {
		linearGradient =`<defs>
		<linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%"
			gradientUnits="userSpaceOnUse">
				<stop offset="0%" stop-color="#fb4d0f"/>
				<stop offset="35%" stop-color="#d1e600"/>
				<stop offset="50%" stop-color="#b0e300"/>
				<stop offset="65%" stop-color="#00e600"/>
				<stop offset="100%" stop-color="#0074ff"/>
		</linearGradient>
		</defs>`
	}

	bar.svg.insertAdjacentHTML("afterBegin", linearGradient);
}