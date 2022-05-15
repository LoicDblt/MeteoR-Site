<?php
// Sécurité
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com");

// Accès à la base de données
include_once "classes/BddDonnees.php";
include_once "classes/BddGraphes.php";
$bddD = new BddDonnees();
$bddG = new BddGraphes();
$valeurs = Array();

// Pour les dates en français
setlocale(LC_ALL, "fr_FR.UTF8");

if (!$_GET){
	include_once "assets/temp.php";
	array_push($valeurs, array($bddD->maxMin("MAX", "max_temp")["date"], $bddD->maxMin("MAX", "max_temp")["max_temp"]));
	array_push($valeurs, array($bddD->maxMin("MIN", "min_temp")["date"], $bddD->maxMin("MIN", "min_temp")["min_temp"]));
}
elseif ($_SERVER["REQUEST_URI"] == "/humidite"){
	include_once "assets/humi.php";
	array_push($valeurs, array($bddD->maxMin("MIN", "min_humi")["date"], $bddD->maxMin("MIN", "min_humi")["min_humi"]));
	array_push($valeurs, array($bddD->maxMin("MAX", "max_humi")["date"], $bddD->maxMin("MAX", "max_humi")["max_humi"]));
}
else{
	header("location: erreur_404");
}

// Fonction de mise en forme
function minMaxDate($date){
	echo ucwords(strftime("%a %-d %b %G à %Hh%m", strtotime($date)));
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
	<meta name="theme-color" content="#f9f9fb" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#010101" media="(prefers-color-scheme: dark)">
	<meta name="description" content="<?=$page["head"]["desc"]?>"/>
	<link rel="manifest" href="meteor.webmanifest"/>
	<link rel="icon" type="image/webp" href="img/meteor_favicon.webp"/>
	<link rel="apple-touch-icon" href="img/meteor_apple_touch.webp">
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
	<div onclick="afficheMinMax()" title="Afficher <?=$page["header"]["minMax"]["title"]?>">
		<div>
			<img src="img/nav/<?=$page["header"]["minMax"]["div1"]["img1"]?>.svg" alt="<?=ucwords($page["header"]["minMax"]["div1"]["img1"])?>"/>
			<img src="img/nav/<?=$page["header"]["minMax"]["div1"]["img2"]?>.svg" alt="<?=ucwords($page["header"]["minMax"]["div1"]["img2"])?>"/>
		</div>
		<div>
			<p title="<?php echo $page["header"]["minMax"]["div1"]["title1"] . " "; echo minMaxDate($valeurs[0][0])?>">
				<?php echo $valeurs[0][1] . $page["commun"]["type"] . "\n"?>
			</p>
			<p title="<?=$page["header"]["minMax"]["div1"]["title2"] . " "; echo minMaxDate($valeurs[1][0])?>">
				<?php echo $valeurs[1][1] . $page["commun"]["type"] . "\n"?>
			</p>
		</div>
	</div>
</header>
<section>
	<section>
		<h1><?=$page["commun"]["nom"]?></h1>
		<p>
			<?php echo $bddD->actu($page["actu"]["tempHumi"]) . $page["commun"]["type"] . "\n"?>
		</p>
	</section>
	<section>
		<h1>Évolution dans le temps</h1>
		<div id="graph">
		</div>
	</section>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.12.1/plotly-basic.min.js" integrity="sha512-1xh2+txa3PenvgKmdLkjsGdZ3gX+RmaAfETw+795FKOpW+DEgnL3GeRKeCXLQrbLNEzPWoR+J2jhMVQm9tYQGQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.12.1/plotly-locale-fr.min.js" integrity="sha512-8i4gvdC9aB88kXdoZiv8knDmCNyCyOiR5JE9lKcYObBGTAs8qCkajAYSf+GpNVu+8GeEwX4254aQjJ8v0cejsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
// Obtention et paramétrage des données
var data =
[{
	x: <?php echo $bddG->graphX()?>,
	y: <?php echo $bddG->graphY($page["actu"]["tempHumi"])?>,
	type: "scatter",
	connectgaps: true,
	line:
	{
		color: "#32a6f5",
		width: 2,
		shape: "spline"
	},
	hovertemplate: "<b><?=$page["commun"]["nom"]?> :</b> %{y:.1f}<?=$page["commun"]["type"]?>" +
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
var t = 10
var r = 10
var b = 50
var l = 55
var bgcolor = "unset"
var gridcolor = "unset"
var color = "unset"

// Paramètres de l'affichage mobile
if (window.matchMedia && window.matchMedia("(max-width: 769px)").matches)
{
	t = 0
	r = 5
	b = 35
	l = 40
}

// Paramètres de l'affichage sombre (si actif au chargement de la page)
if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches)
{
	bgcolor = "#232224"
	gridcolor = "#4a484c"
	color = "#bfbfbf"
}

// Agencement du graphique
var layout =
{
	showlegend: false,
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
		ticksuffix: "<?php
			if ($page["commun"]["type"] == "%")
				echo($page["commun"]["type"] . " ");
			else
				echo($page["commun"]["type"]);
		?>"
	}
}
Plotly.newPlot("graph", data, layout, config)

// Détection dynamique du passage au thème sombre
window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", event =>
{
	var colorScheme = event.matches ? "dark" : "light"
	if (colorScheme == "dark")
	{
		bgcolor = "#232224"
		gridcolor = "#4a484c"
		color = "#bfbfbf"
	}
	else
	{
		bgcolor = "unset"
		gridcolor = "unset"
		color = "unset"
	}
	var layout =
	{
		showlegend: false,
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
			ticksuffix: "<?php
				if ($page["commun"]["type"] == "%")
					echo($page["commun"]["type"] . " ");
				else
					echo($page["commun"]["type"]);
			?>"
		}
	}
	Plotly.newPlot("graph", data, layout, config)
})

// Fonction d'affichage/masquage des valeurs min et max
function afficheMinMax()
{
	let divMinMax = document.querySelector("header > div:last-child > div:last-child")
	let titre = document.querySelector("header > div:last-child")
	$(divMinMax).slideToggle(400, () => {
		if ($(divMinMax).css("display") === "flex")
			titre.title = "Masquer <?=$page["header"]["minMax"]["title"]?>"
		else
			titre.title = "Afficher <?=$page["header"]["minMax"]["title"]?>"
	}).css("display", "flex")
}
</script>
</body>
</html>