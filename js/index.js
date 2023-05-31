// Classe pour les couleurs du graphique
class CouleursGraph {
	constructor(bgcolor, gridcolor, color, linecolor, bordercolor) {
		this.bgcolor = bgcolor;
		this.gridcolor = gridcolor;
		this.color = color;
		this.linecolor = linecolor;
		this.bordercolor = bordercolor;
	}

	getCouleursTableau() {
		return Array(this.bgcolor, this.gridcolor, this.color, this.linecolor,
			this.bordercolor);
	}
}

class CouleursClaires extends CouleursGraph {
	constructor() {
		super("#ffffff", "#eeeeee", "#404040", "#d3d3d3", "#ffffff");
	}
}

class CouleursSombres extends CouleursGraph {
	constructor() {
		super("#000000", "#4a484c", "#bfbfbf", "#565656", "#000000");
	}
}

// Classe pour les couleurs des données
class CouleursDonnees {
	constructor(
		degrade1, degrade2, degrade3, degrade4,
		pourcentage1, pourcentage2, pourcentage3, pourcentage4
	) {
		this.degrade1 = degrade1;
		this.degrade2 = degrade2;
		this.degrade3 = degrade3;
		this.degrade4 = degrade4;
		this.pourcentage1 = pourcentage1;
		this.pourcentage2 = pourcentage2;
		this.pourcentage3 = pourcentage3;
		this.pourcentage4 = pourcentage4;
	}

	getPourcentages() {
		return Array(this.pourcentage1, this.pourcentage2, this.pourcentage3,
			this.pourcentage4);
	}

	getDegrades() {
		return Array(this.degrade1, this.degrade2, this.degrade3,
			this.degrade4);
	}

	getPourcentagesDegrade() {
		return Array(
			Array(this.pourcentage1, this.degrade1),
			Array(this.pourcentage2, this.degrade2),
			Array(this.pourcentage3, this.degrade3),
			Array(this.pourcentage4, this.degrade4)
		);
	}
}

class CouleursDonneesTemp extends CouleursDonnees {
	constructor() {
		super(
			"#0074ff", "#00e600", "#d1e600", "#fb4d0f",
			0.00, 0.40,0.65 ,1.00
		);
	}
}

class CouleursDonneesHum extends CouleursDonnees {
	constructor() {
		super(
			"#fb4d0f", "#d1e600", "#00e600", "#0074ff",
			0.00, 0.20, 0.45, 1.00
		);
	}
}


/**
 * Récupère les données dans la base de données
 * @param nomColonne des données à récupérer
 * @returns les données, ou l'erreur rencontrée
 */
function recupBdd(nomColonne) {
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
 * @min valeur minimale pour le dégradé
 * @max valeur maximale pour le dégradé
 */
function parametrerAfficherGraphique(nomColonne, typeDonnees, unite, min, max) {
	// Configure le graphique
	const config = {
		locale: "fr",
		responsive: true,
		displayModeBar: false,
		showAxisDragHandles: false
	}

	// Configure le placement
	let top, right, bottom, left, nticks;
	if (window.matchMedia && window.matchMedia("(max-width: 769px)").matches) {
		top = 0;
		right = 5;
		bottom = 35;
		left = 40;
		nticks = 5;
	}
	else {
		top = 10;
		right = 10;
		bottom = 50;
		left = 55;
		nticks = 8;
	}

	// Configure les couleurs
	let bgcolor, gridcolor, fontcolor, linecolor, bordercolor;
	if (
		window.matchMedia && window.matchMedia("(prefers-color-scheme: light)")
		.matches
	) {
		[bgcolor, gridcolor, fontcolor, linecolor, bordercolor] =
			(new CouleursClaires()).getCouleursTableau();
	}
	else {
		[bgcolor, gridcolor, fontcolor, linecolor, bordercolor] =
			(new CouleursSombres()).getCouleursTableau();
	}

	// Récupère les couleurs du dégradé
	let degrade;
	if (unite === '%')
		degrade = (new CouleursDonneesHum()).getPourcentagesDegrade();
	else
		degrade = (new CouleursDonneesTemp()).getPourcentagesDegrade();

	// Récupère les données et affiche le graphique
	recupAbsOrd(nomColonne)
	.then(retour => {
		let abscisse = JSON.parse(retour[0]);
		let ordonnee = JSON.parse(retour[1]);

		const data = [{
			x: abscisse,
			y: ordonnee,
			mode: "lines+markers",
			marker: {
				colorscale: degrade,
				color: ordonnee,
				size: 6,
				cmin: min,
				cmax: max
			},
			connectgaps: true,
			line: {
				color: linecolor,
				width: 2,
				shape: "spline"
			},
			hovertemplate: "<b>" + typeDonnees + " :</b> %{y:.1f}" + unite +
							"<br><b>Date :</b> %{x|%a %-d %B à %Hh%M}" +
							"<extra></extra>",
			hoverlabel: {
				align: "left",
				bordercolor: bordercolor,
				font: {
					family: "Open Sans",
					color: "#000000"
				}
			}
		}];

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
			color: fontcolor
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
			],
			range: [abscisse[0], abscisse[abscisse.length - 1]]
		},
		yaxis: {
			gridcolor: gridcolor,
			gridcolorwidth: 1,
			fixedrange: true,
			tickformat: ".1f",
			ticksuffix: (unite === "%") ? unite + " " : unite
		}
	}

		Plotly.newPlot("graphique", data, layout, config);
	})
}


/**
 * Inverse entre afficher ou masquer les valeurs min et max
 * @param nouveauTitre à adapter en fonction de l'affichage
 */
function inverserAffichageMinMax(nouveauTitre) {
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
 * Affiche la jauge représentant la mesure actuelle
 * @param pourcentage de la jauge à remplir
 * @param min de la jauge
 * @param max de la jauge
 * @param unite de la mesure à ajouter à la valeur
 */
function jaugeMesure(pourcentage, min, max, unite) {
	// Permet de recharger la jauge en cas de changement de thème
	let element = document.getElementById("jauge");
	while (element.firstChild)
		element.removeChild(element.firstChild);

	// Configure la couleur de fond 
	if (
		window.matchMedia && window.matchMedia("(prefers-color-scheme: light)")
		.matches
	)
		trailcolor = "#eeeeee";
	else
		trailcolor = "#565656";

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
		pourcentDegrade = couleurDonnes.getPourcentages();
		degrade = couleurDonnes.getDegrades();
	}
	else {
		couleurDonnes = new CouleursDonneesTemp();
		pourcentDegrade = couleurDonnes.getPourcentages();
		degrade = couleurDonnes.getDegrades();
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
 * Active le service worker, sauf sur Firefox bureau
 */
function lancerServiceWorker() {
	if (
		"serviceWorker" in navigator &&
		(window.navigator.userAgent.toLowerCase().indexOf("firefox") === -1 ||
		window.navigator.userAgent.toLowerCase().indexOf("mobile") > -1)
	) {
		navigator.serviceWorker.register("../service_worker.js")
		.then({})
		.catch(function(erreur) {
			console.log("Service worker - enregistrement echoué :", erreur);
		})
	}
}