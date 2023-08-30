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
	let top, right, bottom, left, nTicks;
	if (window.matchMedia("(max-width: 769px)").matches) {
		top = 0;
		right = 7;
		bottom = 35;
		left = 23;
		nTicks = 6;
		tickAngle = -90;
	}
	else {
		top = 10;
		right = 10;
		bottom = 40;
		left = 55;
		nTicks = 8;
		tickAngle = 0;
	}

	// Récupère les couleurs en fonction du thème
	let bgColor, gridColor, fontColor, lineColor, fillColor;
	if (window.matchMedia("(prefers-color-scheme: light)").matches) {
		[bgColor, gridColor, fontColor, lineColor, fillColor] =
			(new CouleursClaires()).getCouleursTableau();
	}
	else {
		[bgColor, gridColor, fontColor, lineColor, fillColor] =
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

		let maxOrd = Math.max.apply(Math, ordonnee) + 5;
		let minOrd = Math.min.apply(Math, ordonnee) - 5;

		const data = [{
			x: abscisse,
			y: ordonnee,
			fill: "tozeroy",
			fillcolor: fillColor,
			mode: "lines+markers",
			connectgaps: true,
			marker: {
				colorscale: degrade,
				color: ordonnee,
				size: 6,
				cmin: min,
				cmax: max
			},
			line: {
				color: lineColor,
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
				color: fontColor
			},
			plot_bgcolor: bgColor,
			paper_bgcolor: bgColor,
			xaxis: {
				showgrid: false,
				nticks: nTicks,
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
				tickangle: tickAngle,
				zeroline: false,
				gridcolor: gridColor,
				gridcolorwidth: 1,
				range: [minOrd, maxOrd],
				nticks: nTicks,
				fixedrange: true,
				tickformat: ".1f",
				ticksuffix: (unite === "%") ? unite + " " : unite
			}
		}

		// Affiche le graphique
		Plotly.newPlot("graphique", data, layout, config);
	})
}
