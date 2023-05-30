<?php
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com");

include_once "classes/BddDonnees.php";
$bddDonnees = new BddDonnees();

$valeursMinMax = Array();
const CHEMIN_DOSSIER_NAV = "img/nav/";

// Adapte le contenu de la page en fonction de l'URL (température ou humidité)
if ($_SERVER["REQUEST_URI"] == "/"){
	include_once "assets/temperature.php";

	// Récupère les valeurs max puis min (température)
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MAX", "max_temp"));
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MIN", "min_temp"));
	$barreMinMax = [10, 30];
}
else if ($_SERVER["REQUEST_URI"] == "/humidite"){
	include_once "assets/humidite.php";

	// Récupère les valeurs min puis max (humidité)
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MIN", "min_humi"));
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MAX", "max_humi"));
	$barreMinMax = [20, 80];
}

// Récupère la valeur actuelle
$valeurActu = $bddDonnees->getValeurActu(CONTENU_PAGE["commun"]["nomColonne"]);

// Adapte les valeurs min et max de la barre de progression
// (pour qu'elles soient toujours visibles)
if ($valeurActu[1] <= $barreMinMax[0])
	$barreMinMax[0] = $valeurActu[1] - 1;

else if ($valeurActu[1] >= $barreMinMax[1])
	$barreMinMax[1] = $valeurActu[1];


/**
 * Formate une date en chaîne de caractères, au format français
 * @param string $date Date à formater
 *
 * @return string Date formatée
 */
function formatageDate($date) : string {
	$formatter = new IntlDateFormatter("fr_FR",
		IntlDateFormatter::FULL,
		IntlDateFormatter::NONE,
		"Europe/Paris",
		IntlDateFormatter::GREGORIAN,
		"EE d MMMM y 'à' kk'h'mm");

	// Capitalise les mots, et supprime les points
	return ucwords(str_replace(".", "", $formatter->format(strtotime($date))));
}


/**
 * Formate une valeur numérique en chaîne de caractères
 * @param float $valeur Valeur à formater
 *
 * @return string Valeur formatée
 */
function formatageValeur($valeur) : string {
	return number_format($valeur, 1);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title><?php echo CONTENU_PAGE["commun"]["typeDonnees"]?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)"/>
	<meta name="description" content="<?php echo CONTENU_PAGE["head"]["desc"]?>"/>
	<link rel="manifest" href="meteor.webmanifest"/>
	<link rel="icon" type="image/webp" href="img/icons/favicon.webp"/>
	<link rel="apple-touch-icon" type="image/webp" href="img/icons/apple_touch.webp"/>
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
</head>
<body>
<header>
	<nav>
		<a draggable="false" href="<?php echo CONTENU_PAGE["nav"]["href"]?>">
			<?php echo CONTENU_PAGE["nav"]["valeur"] . PHP_EOL?>
		</a>
	</nav>
	<div id="boxCentre">
		<img draggable="false" src="<?php echo
			CHEMIN_DOSSIER_NAV?>meteor.svg" alt="Logo du site MeteoR"
		/>
	</div>
	<div id="boxDroite" onclick= "inverserAffichageMinMax(`<?php echo
		// Garder les apostrophes sur balise php, pour la coloration syntaxique
		CONTENU_PAGE['minMax']['titre']?>`)" title="Afficher <?php echo
		CONTENU_PAGE["minMax"]["titre"]
	?>">
		<div>
			<img draggable="false" src=<?php echo
				"\"" . CHEMIN_DOSSIER_NAV .
				CONTENU_PAGE["minMax"]["divGauche"]["img"]?>.svg" alt=<?php echo
				"\"" . ucwords(CONTENU_PAGE["minMax"]["divGauche"]["img"])
			?>"/>
			<img draggable="false" src=<?php echo
				"\"" . CHEMIN_DOSSIER_NAV .
				CONTENU_PAGE["minMax"]["divDroite"]["img"]?>.svg" alt=<?php echo
				"\"" . ucwords(CONTENU_PAGE["minMax"]["divDroite"]["img"])
			?>"/>
		</div>
		<div id="valeursMinMax">
			<p title="<?php
				// 2 echo séparés, sinon ordre d'affichage incorrect !
				echo CONTENU_PAGE["minMax"]["divGauche"]["titre"] . " ";
				echo formatageDate($valeursMinMax[0][0]);
			?>">
				<?php echo
					formatageValeur($valeursMinMax[0][1]) .
					CONTENU_PAGE["commun"]["unite"] .
					PHP_EOL
				?>
			</p>
			<p title="<?php
				echo CONTENU_PAGE["minMax"]["divDroite"]["titre"] . " ";
				echo formatageDate($valeursMinMax[1][0])
			?>">
				<?php echo
					formatageValeur($valeursMinMax[1][1]) .
					CONTENU_PAGE["commun"]["unite"] . PHP_EOL
				?>
			</p>
		</div>
	</div>
</header>
<section>
	<section>
		<h1><?php echo CONTENU_PAGE["commun"]["typeDonnees"]?></h1>
		<div title="<?php
			echo CONTENU_PAGE["commun"]["titreActu"] . " ";
			echo formatageDate($valeurActu[0])?>" id="container">
		</div>
	</section>
	<section>
		<h1>Évolution dans le temps</h1>
		<div id="graphique"></div>
	</section>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.23.1/plotly-basic.min.js"
integrity="sha512-f0oboR/rYzxj/vXcuRFpw5KOsk8kf+MogGuKnaWw9aC6dQAgEi77rHdo407YvoZ1PLhWvHCOb+zKuz7uML0azQ=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.23.1/plotly-locale-fr.min.js" integrity="sha512-nyAFXuhmcYPFCAawwaZOW22viMZW5Aw1jB7w84GbnbPqIz1SDHWGdQw17DB2BfU1jv4nnEdJgvolNINTjdSKMA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"
integrity="sha512-EZhmSl/hiKyEHklogkakFnSYa5mWsLmTC4ZfvVzhqYNLPbXKAXsjUYRf2O9OlzQN33H0xBVfGSEIUeqt9astHQ=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="js/index.js"></script>
<script>
parametrerAfficherGraphique(<?php echo "\"" .
	CONTENU_PAGE["commun"]["nomColonne"] . "\", \"" .
	CONTENU_PAGE["commun"]["typeDonnees"] . "\", \"" .
	CONTENU_PAGE["commun"]["unite"] . "\""
?>);

window.matchMedia("(prefers-color-scheme: light)").addEventListener("change",
() => {
	parametrerAfficherGraphique(<?php echo "\"" .
		CONTENU_PAGE["commun"]["nomColonne"] . "\", \"" .
		CONTENU_PAGE["commun"]["typeDonnees"] . "\", \"" .
		CONTENU_PAGE["commun"]["unite"] . "\""
	?>);
});

barreProgression(<?php echo
	(((($valeurActu[1] - $barreMinMax[0]) * 100) /
		($barreMinMax[1] - $barreMinMax[0])) / 100) . ", " .
	$barreMinMax[0] . ", " .
	$barreMinMax[1] . ", \"" .
	CONTENU_PAGE["commun"]["unite"] . "\""
?>);

lancerServiceWorker();
</script>
</body>
</html>