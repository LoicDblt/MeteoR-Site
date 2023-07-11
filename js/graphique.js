/**
 * Paramètre le graphique, récupère les données et affiche l'ensemble
 * 
 * @param nomColonne dans la base de données, des mesures à récupérer
 * @param typeMesures des mesures, à afficher dans les labels
 * @param unite des mesures, à afficher sur l'axe des ordonnées
 * @param min valeur minimale pour le dégradé
 * @param max valeur maximale pour le dégradé
 */
function parametrerAfficherGraphique(nomColonne, typeMesures, unite, min, max) {
	// Configure les paramètres du graphique
	const config = {
		locale: "fr",
		responsive: true,
		displayModeBar: false,
		showAxisDragHandles: false
	}

	// Configure le placement du graphique
	let top, right, bottom, left, nticks;
	if (window.matchMedia("(max-width: 769px)").matches) {
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

	// Récupère les couleurs en fonction du thème
	let bgcolor, gridcolor, fontcolor, linecolor;
	if (window.matchMedia("(prefers-color-scheme: light)").matches) {
		[bgcolor, gridcolor, fontcolor, linecolor] =
			(new CouleursClaires()).getCouleursTableau();
	}
	else {
		[bgcolor, gridcolor, fontcolor, linecolor] =
			(new CouleursSombres()).getCouleursTableau();
	}

	// Récupère les couleurs du dégradé en fonction du type de données
	let degrade;
	if (unite === '%') {
		degrade = (new CouleursDonneesHum()).getPairesPourcentCouleur();
	}
	else {
		degrade = (new CouleursDonneesTemp()).getPairesPourcentCouleur();
	}

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
			hovertemplate: "<b>" + typeMesures + " :</b> %{y:.1f}" + unite +
							"<br><b>Date :</b> %{x|%a %-d %B à %Hh%M}" +
							"<extra></extra>",
			hoverlabel: {
				align: "left",
				bordercolor: "transparent",
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

		// Affiche le graphique
		Plotly.newPlot("graphique", data, layout, config);
	})
}
