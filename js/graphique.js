/**
 * Paramètre le graphique, récupère les données et affiche l'ensemble
 *
 * @param nomColonne dans la base de données, des mesures à récupérer
 * @param typeMesures des mesures, à afficher dans les labels
 * @param unite des mesures, à afficher sur l'axe des ordonnées
 * @param min du dégradé
 * @param max du dégradé
 */
function parametrerAfficherGraphique(nomColonne, typeMesures, unite, min, max) {
	// Configure les paramètres du graphique
	const config = {
		locale: "fr",
		responsive: true,
		displayModeBar: false,
		showAxisDragHandles: false
	}

	// Configure le graphique en fonction de la taille de l'écran
	let [top, right, bottom, left, nTicks, tickAngle, formatMois] =
		(window.matchMedia("(max-width: 769px)").matches) ?
		[0, 7, 35, 23, 6, -70, "%b"] : [10, 10, 40, 55, 8, 0, "%B"];

	// Récupère les couleurs en fonction du thème
	let couleursGraph =
		(window.matchMedia("(prefers-color-scheme: light)").matches) ?
		new CouleursClaires() : new CouleursSombres();
	let [bgColor, gridColor, fontColor, lineColor, fillColor] =
		couleursGraph.getCouleursTableau();

	// Récupère les couleurs du dégradé en fonction du type de données
	let degrade = (unite === '%') ?
		(new CouleursDonneesHum()) : (new CouleursDonneesTemp());

	// Récupère les données et affiche le graphique
	recupAbsOrd(nomColonne)
	.then(retour => {
		let abscisse = JSON.parse(retour[0]);
		let ordonnee = JSON.parse(retour[1]);

		// Détermine la valeur minimale de l'axe des ordonnées
		let maxOrd = Math.max.apply(Math, ordonnee) + 5;
		let minOrd = Math.min.apply(Math, ordonnee) - 5;

		// Zoom par défaut sur la dernière semaine pour la version mobile
		let rangeMin = window.matchMedia("(max-width: 769px)").matches ?
			abscisse[abscisse.length - (7 * 24) - 1] : abscisse[0];

		const data = [{
			x: abscisse,
			y: ordonnee,
			fill: "tozeroy",
			fillcolor: fillColor,
			mode: "lines+markers",
			connectgaps: true,
			marker: {
				colorscale: degrade.getPairesPourcentCouleur(),
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
							"<br><b>Date :</b> %{x|%a %-d " + formatMois +
							" à %Hh%M}<extra></extra>",
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
			plot_bgcolor: bgColor,
			paper_bgcolor: bgColor,
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
			xaxis: {
				showgrid: false,
				nticks: nTicks,
				tickangle: 0,
				range: [rangeMin, abscisse[abscisse.length - 1]],
				tickformatstops: [
					{
						"dtickrange": [null, 60000],
						"value": "%-d " + formatMois + "<br>%Hh%M.%S"
					},
					{
						"dtickrange": [60000, 3600000],
						"value": "%-d " + formatMois + "<br>%Hh%M"
					},
					{
						"dtickrange": [3600000, 86400000],
						"value": "%-d " + formatMois + "<br>%Hh%M"
					},
					{
						"dtickrange": [86400000, null],
						"value": "%-d " + formatMois
					}
				]
			},
			yaxis: {
				gridcolor: gridColor,
				gridcolorwidth: 1,
				range: [minOrd, maxOrd],
				nticks: nTicks,
				tickangle: tickAngle,
				zeroline: false,
				fixedrange: true,
				tickformat: ".1f",
				ticksuffix: (unite === "%") ? unite + " " : unite
			}
		}

		// Affiche le graphique
		Plotly.newPlot("graphique", data, layout, config);
	})
}