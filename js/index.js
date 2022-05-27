// Fonction de paramétrage et d'affichage du graphique
function generationGraphique(x, y, type, unite)
{
	var data =
	[{
		x: x,
		y: y,
		type: "scatter",
		connectgaps: true,
		line:
		{
			color: "#32a6f5",
			width: 2,
			shape: "spline"
		},
		hovertemplate: "<b>" + type + " :</b> %{y:.1f}" + unite +
						"<br><b>Date :</b> %{x|%a %-d %b à %Hh%M}" +
						"<extra></extra>",
		hoverlabel:
		{
			align: "left",
			bordercolor: "#32a6f5",
			font:
			{
				family: "Open Sans",
				color: "#ffffff"
			}
		},
		showlegend: false
	}]

	// Configuration du graphique
	var config =
	{
		locale: "fr",
		displayModeBar: false,
		responsive: true,
		showAxisDragHandles: false
	}

	// Paramétrage des variables du graphique
	var t = 10;
	var r = 10;
	var b = 50;
	var l = 55;
	var bgcolor = "unset";
	var gridcolor = "unset";
	var color = "unset";

	// Paramètres de l'affichage mobile
	if (window.matchMedia && window.matchMedia("(max-width: 769px)").matches)
	{
		t = 0;
		r = 5;
		b = 35;
		l = 40;
	}

	// Paramètres de l'affichage sombre (si actif au chargement de la page)
	if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches)
	{
		bgcolor = "#232224";
		gridcolor = "#4a484c";
		color = "#bfbfbf";
	}

	// Agencement du graphique
	var layout =
	{
		showlegend: false,
		separators: ".,",
		margin:
		{
			t: t,
			r: r,
			b: b,
			l: l,
			pad: 4
		},
		font:
		{
			family: "Open Sans",
			color: color
		},
		plot_bgcolor: bgcolor,
		paper_bgcolor: bgcolor,
		xaxis:
		{
			showgrid: false
		},
		yaxis:
		{
			gridcolor: gridcolor,
			gridcolorwidth: 1,
			fixedrange: true,
			tickformat: ".1f",
			ticksuffix: (unite == "%") ? unite + " " : unite
		}
	}
	Plotly.newPlot("graph", data, layout, config);

	// Détection dynamique du passage au thème sombre
	window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", event =>
	{
		var colorScheme = event.matches ? "dark" : "light";
		if (colorScheme == "dark")
		{
			bgcolor = "#232224";
			gridcolor = "#4a484c";
			color = "#bfbfbf";
		}
		else
		{
			bgcolor = "unset";
			gridcolor = "unset";
			color = "unset";
		}
		var layout =
		{
			showlegend: false,
			separators: ".,",
			margin:
			{
				t: t,
				r: r,
				b: b,
				l: l,
				pad: 4
			},
			font:
			{
				family: "Open Sans",
				color: color
			},
			plot_bgcolor: bgcolor,
			paper_bgcolor: bgcolor,
			xaxis:
			{
				showgrid: false
			},
			yaxis:
			{
				gridcolor: gridcolor,
				gridcolorwidth: 1,
				fixedrange: true,
				tickformat: ".1f",
				ticksuffix: (unite == "%") ? unite + " " : unite
			}
		}
		Plotly.newPlot("graph", data, layout, config);
	})
}

// Fonction d'affichage/masquage des valeurs min et max
function afficheMinMax(message)
{
	let divMinMax = document.querySelector("header > div:last-child > div:last-child");
	let titre = document.querySelector("header > div:last-child");
	$(divMinMax).slideToggle(400, () => {
		if ($(divMinMax).css("display") === "flex")
			titre.title = "Masquer " + message;
		else
			titre.title = "Afficher " + message;
	}).css("display", "flex");
}

// Lance le service worker, si disponible
if ("serviceWorker" in navigator)
{
	navigator.serviceWorker.register("../service_worker.js")
	.then({})
	.catch(function(erreur)
	{
		console.log("Service worker - enregistrement echoué :", erreur);
	})
}