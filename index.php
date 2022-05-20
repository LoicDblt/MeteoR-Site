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

if (!$_GET){
	include_once "assets/temp.php";
	array_push($valeurs, array($bddD->minMax("MAX", "max_temp")["date"], $bddD->minMax("MAX", "max_temp")["max_temp"]));
	array_push($valeurs, array($bddD->minMax("MIN", "min_temp")["date"], $bddD->minMax("MIN", "min_temp")["min_temp"]));
}
elseif ($_SERVER["REQUEST_URI"] == "/humidite"){
	include_once "assets/humi.php";
	array_push($valeurs, array($bddD->minMax("MIN", "min_humi")["date"], $bddD->minMax("MIN", "min_humi")["min_humi"]));
	array_push($valeurs, array($bddD->minMax("MAX", "max_humi")["date"], $bddD->minMax("MAX", "max_humi")["max_humi"]));
}
else{
	header("location: erreur_404");
}

// Fonction de mise en forme des dates
function minMaxDate($date){
	$formatter = new IntlDateFormatter("fr_FR",
		IntlDateFormatter::FULL,
		IntlDateFormatter::NONE,
		"Europe/Paris",
		IntlDateFormatter::GREGORIAN,
		"EE dd MMM y 'à' kk'h'mm");
	// Capitalise les mots, et supprime les points
	echo ucwords(str_replace(".", "", $formatter->format(strtotime($date))));
}

// Fonction de mise en forme des valeurs de température et d'humidité
function tempHumiValeur($valeur){
	echo number_format($valeur, 1);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title><?php echo $page["commun"]["nom"]?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#f9f9fb" media="(prefers-color-scheme: light)">
	<meta name="theme-color" content="#010101" media="(prefers-color-scheme: dark)">
	<meta name="description" content="<?php echo $page["head"]["desc"]?>"/>
	<link rel="manifest" href="meteor.webmanifest"/>
	<link rel="icon" type="image/webp" href="img/meteor_favicon.webp"/>
	<link rel="apple-touch-icon" href="img/meteor_apple_touch.webp">
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
</head>
<body>
<header>
	<nav>
		<a href="<?php echo $page["header"]["nav"]["href"]?>"><?php echo $page["header"]["nav"]["valeur"]?></a>
	</nav>
	<div>
		<img src="img/nav/meteor.webp" alt="Logo du site"/>
	</div>
	<div onclick="afficheMinMax('<?php echo $page["header"]["minMax"]["title"]?>')" title="Afficher <?php echo $page["header"]["minMax"]["title"]?>">
		<div>
			<img src="img/nav/<?php echo $page["header"]["minMax"]["div1"]["img1"]?>.svg" alt="<?php echo ucwords($page["header"]["minMax"]["div1"]["img1"])?>"/>
			<img src="img/nav/<?php echo $page["header"]["minMax"]["div1"]["img2"]?>.svg" alt="<?php echo ucwords($page["header"]["minMax"]["div1"]["img2"])?>"/>
		</div>
		<div>
			<p title="<?php
				echo $page["header"]["minMax"]["div1"]["title1"] . " ";
				echo minMaxDate($valeurs[0][0])
				// 2 echo séparés, sinon ordre d'affichage incorrect !?>">
				<?php echo tempHumiValeur($valeurs[0][1]) . $page["commun"]["type"] . PHP_EOL?>
			</p>
			<p title="<?php
				echo $page["header"]["minMax"]["div1"]["title2"] . " ";
				echo minMaxDate($valeurs[1][0])?>">
				<?php echo tempHumiValeur($valeurs[1][1]) . $page["commun"]["type"] . PHP_EOL?>
			</p>
		</div>
	</div>
</header>
<section>
	<section>
		<h1><?php echo $page["commun"]["nom"]?></h1>
		<p>
			<?php echo tempHumiValeur($bddD->actu($page["actu"]["tempHumi"])) . $page["commun"]["type"] . PHP_EOL?>
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
<script src="javascript/index.js"></script>
<script>
let x = <?php echo $bddG->graphX()?>;
let y = <?php echo $bddG->graphY($page["actu"]["tempHumi"])?>;
graphique(x, y, "<?php echo $page["commun"]["nom"]?>", "<?php echo $page["commun"]["type"]?>");
</script>
</body>
</html>