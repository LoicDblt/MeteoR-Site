<?php
// Sécurité
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com");

// Accès à la base de données
include_once "classes/BddDonnees.php";
include_once "classes/BddGraphes.php";
$bddDonnees = new BddDonnees();
$bddGraph = new BddGraphes();
$valeurs = Array();

if (!$_GET){
	include_once "assets/temp.php";
	array_push($valeurs, array($bddDonnees->getMinMax("MAX", "max_temp")["date"], $bddDonnees->getMinMax("MAX", "max_temp")["max_temp"]));
	array_push($valeurs, array($bddDonnees->getMinMax("MIN", "min_temp")["date"], $bddDonnees->getMinMax("MIN", "min_temp")["min_temp"]));
}
elseif ($_SERVER["REQUEST_URI"] == "/humidite"){
	include_once "assets/humi.php";
	array_push($valeurs, array($bddDonnees->getMinMax("MIN", "min_humi")["date"], $bddDonnees->getMinMax("MIN", "min_humi")["min_humi"]));
	array_push($valeurs, array($bddDonnees->getMinMax("MAX", "max_humi")["date"], $bddDonnees->getMinMax("MAX", "max_humi")["max_humi"]));
}

// Fonction de mise en forme des dates
function formatageDate($date){
	$formatter = new IntlDateFormatter("fr_FR",
		IntlDateFormatter::FULL,
		IntlDateFormatter::NONE,
		"Europe/Paris",
		IntlDateFormatter::GREGORIAN,
		"EE d MMM y 'à' kk'h'mm");

	// Capitalise les mots, et supprime les points
	echo ucwords(str_replace(".", "", $formatter->format(strtotime($date))));
}

// Fonction de mise en forme des valeurs de température et d'humidité
function formatageValeur($valeur){
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
	<link rel="icon" type="image/webp" href="img/icons/meteor_favicon.webp"/>
	<link rel="apple-touch-icon" href="img/icons/meteor_apple_touch.webp">
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
</head>
<body>
<header>
	<nav>
		<a href="<?php echo $page["header"]["nav"]["href"]?>"><?php echo $page["header"]["nav"]["valeur"]?></a>
	</nav>
	<div>
		<img src="img/nav/meteor.webp" alt="Logo de MeteoR"/>
	</div>
	<div onclick="afficheMinMax(`<?php echo $page["header"]["minMax"]["titre"]?>`)" title="Afficher <?php echo $page["header"]["minMax"]["titre"]?>">
		<div>
			<img src="img/nav/<?php echo $page["header"]["minMax"]["divGauche"]["img"]?>.svg" alt="<?php echo ucwords($page["header"]["minMax"]["divGauche"]["img"])?>"/>
			<img src="img/nav/<?php echo $page["header"]["minMax"]["divDroite"]["img"]?>.svg" alt="<?php echo ucwords($page["header"]["minMax"]["divDroite"]["img"])?>"/>
		</div>
		<div>
			<p title="<?php
				echo $page["header"]["minMax"]["divGauche"]["titre"] . " ";
				echo formatageDate($valeurs[0][0]);
				// 2 echo séparés, sinon ordre d'affichage incorrect !?>">
				<?php echo formatageValeur($valeurs[0][1]) . $page["commun"]["unite"] . PHP_EOL?>
			</p>
			<p title="<?php
				echo $page["header"]["minMax"]["divDroite"]["titre"] . " ";
				echo formatageDate($valeurs[1][0])?>">
				<?php echo formatageValeur($valeurs[1][1]) . $page["commun"]["unite"] . PHP_EOL?>
			</p>
		</div>
	</div>
</header>
<section>
	<section>
		<h1><?php echo $page["commun"]["nom"]?></h1>
		<p>
			<?php echo formatageValeur($bddDonnees->getActu($page["commun"]["tempHumi"])) . $page["commun"]["unite"] . PHP_EOL?>
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
<script src="js/index.js"></script>
<script>
let x = <?php echo $bddGraph->getGraph("date_mesure")?>;
let y = <?php echo $bddGraph->getGraph($page["commun"]["tempHumi"])?>;
generationGraphique(x, y, "<?php echo $page["commun"]["nom"]?>", "<?php echo $page["commun"]["unite"]?>");
</script>
</body>
</html>