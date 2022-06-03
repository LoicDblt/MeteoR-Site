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

function parametrageAffichageGraphique(pointsAbscisse, pointsOrdonnee, type,
unite){
	var data = [{
		x: pointsAbscisse,
		y: pointsOrdonnee,
		type: "scatter",
		connectgaps: true,
		line: {
			color: "#32a6f5",
			width: 2,
			shape: "spline"
		},
		hovertemplate: "<b>" + type + " :</b> %{y:.1f}" + unite +
						"<br><b>Date :</b> %{x|%a %-d %b à %Hh%M}" +
						"<extra></extra>",
		hoverlabel: {
			align: "left",
			bordercolor: "#32a6f5",
			font:{
				family: "Open Sans",
				color: "#ffffff"
			}
		},
		showlegend: false
	}]

	var config = {
		locale: "fr",
		displayModeBar: false,
		responsive: true,
		showAxisDragHandles: false
	}

	var top = 10;
	var right = 10;
	var bottom = 50;
	var left = 55;

	var couleurs = new CouleursClaires();
	var bgcolor;
	var gridcolor;
	var color;
	[bgcolor, gridcolor, color] = couleurs.getTableauToutesCouleurs();

	if (window.matchMedia && window.matchMedia("(max-width: 769px)").matches){
		top = 0;
		right = 5;
		bottom = 35;
		left = 40;
	}

	if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)")
	.matches){
		couleurs = new CouleursSombres();
		[bgcolor, gridcolor, color] = couleurs.getTableauToutesCouleurs();
	}

	var layout = {
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
			showgrid: false
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

	// Détection dynamique de changement de thème
	window.matchMedia("(prefers-color-scheme: dark)")
	.addEventListener("change", event => {
		var colorScheme = event.matches ? "dark" : "light";
		if (colorScheme == "dark") {
			couleurs = new CouleursSombres();
			[bgcolor, gridcolor, color] = couleurs.getTableauToutesCouleurs();
		}
		else {
			couleurs = new CouleursClaires();
			[bgcolor, gridcolor, color] = couleurs.getTableauToutesCouleurs();
		}
		var layout = {
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
				showgrid: false
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
	})
}

function inverserAffichageMinMax(nouveauTitre){
	let titre = document.getElementById("boxDroite");
	let divMinMax = document.getElementById("valeursMinMax");
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