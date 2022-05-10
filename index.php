<?php
// Sécurité
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com ajax.cloudflare.com cdn.plot.ly");

// Accès à la base de données
include_once "classe/BddDonnees.php";
include_once "classe/BddGraphes.php";
$bddD = new BddDonnees();
$bddG = new BddGraphes();
$valeurs = Array();

if (!$_GET){
	include_once "assets/temp.php";
	array_push($valeurs, array($bddD->maxMin("MAX", "max_temp")["date"], $bddD->maxMin("MAX", "max_temp")["max_temp"]));
	array_push($valeurs, array($bddD->maxMin("MIN", "min_temp")["date"], $bddD->maxMin("MIN", "min_temp")["min_temp"]));
}
elseif (isset($_GET["humi"])){
	include_once "assets/humi.php";
	array_push($valeurs, array($bddD->maxMin("MIN", "min_humi")["date"], $bddD->maxMin("MIN", "min_humi")["min_humi"]));
	array_push($valeurs, array($bddD->maxMin("MAX", "max_humi")["date"], $bddD->maxMin("MAX", "max_humi")["max_humi"]));
}
else{
	header("location: erreur_404");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title>MeteoR - <?=$page["commun"]["nom"]?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#F9F9FB"/>
	<meta name="description" content="<?=$page["head"]["desc"]?>"/>
	<link rel="manifest" href="meteor.webmanifest"/>
	<link rel="icon" type="image/webp" href="img/meteor_favicon.webp"/>
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
</head>
<body>
<header>
	<nav>
		<a href="<?=$page["header"]["nav"]["href"]?>"><?=$page["header"]["nav"]["valeur"]?></a>
	</nav>
	<div>
		<img src="img/nav/meteor.webp" alt="Logo du site"/>
	</div>
	<div onclick="affiche_min_max()" title="Cliquez pour afficher les extrêmes <?=$page["header"]["minMax"]["title"]?>">
		<div>
			<img src="img/nav/<?=$page["header"]["minMax"]["div1"]["img1"]?>.webp" alt="<?=$page["header"]["minMax"]["div1"]["alt1"]?>" title="<?=$page["header"]["minMax"]["div1"]["title1"]?> <?php echo $valeurs[0][0]?>"/>
			<img src="img/nav/<?=$page["header"]["minMax"]["div1"]["img2"]?>.webp" alt="<?=$page["header"]["minMax"]["div1"]["alt2"]?>" title="<?=$page["header"]["minMax"]["div1"]["title2"]?> <?php echo $valeurs[1][0]?>"/>
		</div>
		<div>
			<p>
				<?php echo $valeurs[0][1] . $page["commun"]["type"]?>
			</p>
			<p>
				<?php echo $valeurs[1][1] . $page["commun"]["type"]?>
			</p>
		</div>
	</div>
</header>
<section>
	<section>
		<h1><?=$page["commun"]["nom"]?></h1>
		<p>
			<?php echo $bddD->actu($page["actu"]["tempHumi"]) . $page["commun"]["type"]?>
		</p>
	</section>
	<section>
		<h1>Évolution dans le temps</h1>
		<div id="graph">
		</div>
	</section>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.plot.ly/plotly-2.11.1.min.js"></script>
<script src="https://cdn.plot.ly/plotly-locale-fr-latest.js"></script>
<script>
// Récupération des données dans le backend
let x = <?php echo $bddG->graphX()?>;
let tabX = new Array()
x.forEach(element =>
	tabX.push(Object.values(element)[0])
)
let y = <?php echo $bddG->graphY($page["actu"]["tempHumi"])?>;
let tabY = new Array()
y.forEach(element =>
	tabY.push(Object.values(element)[0])
)

// Paramétrage des données
var data = [{
	x: tabX,
	y: tabY,
	type: "scatter",
	line: {
		color: "#32a6f5",
		width: 2,
		shape: "spline"
	},
	hovertemplate: "<b><?=$page["commun"]["nom"]?> :</b> %{y:.2f}<?=$page["commun"]["type"]?>" +
					"<br><b>Date :</b> %{x|%a%e %B à %Hh%M}" +
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

// Paramétrage du graphique
if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
	var layout = {
		showlegend: false,
		margin: {
			t: 15,
			r: 15,
			b: 50,
			l: 50,
			pad: 4
		},
		font: {
			family: "Open Sans",
			size: 15,
			color: "#BFBFBF"
		},
		plot_bgcolor:"#232224",
		paper_bgcolor:"#232224",
		xaxis: {
			showgrid: false,
		},
		yaxis: {
			gridcolor: "#4a484c",
			gridcolorwidth: 1,
			fixedrange: true
		}
	}
}
else{
	var layout = {
		showlegend: false,
		margin: {
			t: 15,
			r: 15,
			b: 50,
			l: 50,
			pad: 4
		},
		font: {
			family: "Open Sans",
			size: 15,
			color: "#000000"
		},
		xaxis: {
			showgrid: false
		},
		yaxis: {
			fixedrange: true
		}
	}
}

// Détection changement de thème
window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", event => {
	var colorScheme = event.matches ? "dark" : "light"
	if (colorScheme == "dark"){
		var layout = {
			showlegend: false,
			margin: {
				t: 15,
				r: 15,
				b: 50,
				l: 50,
				pad: 4
			},
			font: {
				family: "Open Sans",
				size: 15,
				color: "#BFBFBF"
			},
			plot_bgcolor:"#232224",
			paper_bgcolor:"#232224",
			xaxis: {
				showgrid: false,
			},
			yaxis: {
				gridcolor: "#4a484c",
				gridcolorwidth: 1,
				fixedrange: true
			}
		}
		Plotly.newPlot("graph", data, layout, config)
	}
	else{
		var layout = {
			showlegend: false,
			margin: {
				t: 15,
				r: 15,
				b: 50,
				l: 50,
				pad: 4
			},
			font: {
				family: "Open Sans",
				size: 15,
				color: "#000000"
			},
			xaxis: {
				showgrid: false,
			},
			yaxis: {
				fixedrange: true
			}
		}
		Plotly.newPlot("graph", data, layout, config)
	}
})

// Configuration générale du graphique
var config = {
	locale: "fr",
	displayModeBar: false,
	responsive: true
}
Plotly.newPlot("graph", data, layout, config)

// Fonction d'affichage/masquage des min et max
function affiche_min_max(){
	$(document.querySelector("header > div:last-child > div:last-child")).slideToggle(400).css("display", "flex")
}
</script>
</body>
</html>