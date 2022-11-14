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
		super("#232224", "#4a484c", "#bfbfbf");
	}
}

function parametrerAfficherGraphique(
	pointsAbscisse, pointsOrdonnee,
	typeDonnees, unite
){
	const config = {
		locale: "fr",
		displayModeBar: false,
		responsive: true,
		showAxisDragHandles: false
	}

	const data = [{
		x: pointsAbscisse,
		y: pointsOrdonnee,
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
			ticksuffix: (unite == "%") ? unite + " " : unite
		}
	}
	Plotly.newPlot("graphique", data, layout, config);
}

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

// N'active pas le service worker sur Firefox bureau -> problème de perfs
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