<?php
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com");

include_once "classes/BddMesures.php";
$bddMesures = new BddMesures();

$valeursMinMax = Array();
const CHEMIN_DOSSIER_NAV = "img/nav/";

// Adapte le contenu de la page en fonction de l'URL (température ou humidité)
if ($_SERVER["REQUEST_URI"] === "/") {
	include_once "assets/temperature.php";

	// Récupère les valeurs max puis min (température)
	array_push($valeursMinMax, $bddMesures->getValeurMinMax("MAX", "max_temp"));
	array_push($valeursMinMax, $bddMesures->getValeurMinMax("MIN", "min_temp"));

	// Détermine les valeurs min et max de la jauge
	$jaugeMinMax = [10, 30];
}
else if ($_SERVER["REQUEST_URI"] === "/humidite") {
	include_once "assets/humidite.php";

	// Récupère les valeurs min puis max (humidité)
	array_push($valeursMinMax, $bddMesures->getValeurMinMax("MIN", "min_humi"));
	array_push($valeursMinMax, $bddMesures->getValeurMinMax("MAX", "max_humi"));

	// Détermine les valeurs min et max de la jauge
	$jaugeMinMax = [25, 75];
}
else {
	// En cas de paramètre d'URL invalide, renvoie une erreur 404
	header("HTTP/1.0 404 Not Found");
	include("erreur_404.html");
	die();
}

// Récupère la valeur actuelle
$valeurActu = $bddMesures->getValeurActu(CONTENU_PAGE["commun"]["nomColonne"]);

// Adapte les valeurs min et max de la jauge de mesure, afin qu'elles soient
// toujours visibles
if ($valeurActu[1] <= $jaugeMinMax[0]) {
	$jaugeMinMax[0] = $valeurActu[1] - 1;
}
else if ($valeurActu[1] >= $jaugeMinMax[1]) {
	$jaugeMinMax[1] = $valeurActu[1];
}


/**
 * Formate une date en chaîne de caractères, au format français
 *
 * @param string $date Date à formater
 *
 * @return string Date formatée
 */
function formatageDate($date) : string {
	$formatage = new IntlDateFormatter(
		"fr_FR",
		IntlDateFormatter::FULL,
		IntlDateFormatter::NONE,
		"Europe/Paris",
		IntlDateFormatter::GREGORIAN,
		"EEEE d MMMM y 'à' HH'h'mm"
	);

	// Capitalise les mots, et supprime les points
	return ucwords(str_replace('.', '', $formatage->format(strtotime($date))));
}


/**
 * Formate une valeur numérique en chaîne de caractères
 *
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
	<title><?php echo CONTENU_PAGE["commun"]["typeMesures"]?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#fbfbfb" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#07070a" media="(prefers-color-scheme: dark)"/>
	<meta name="description" content="<?php echo CONTENU_PAGE["head"]["desc"]?>"/>
	<link rel="manifest" href="meteor.webmanifest"/>
	<link rel="icon" type="image/webp" href="img/icons/favicon.webp"/>
	<link rel="apple-touch-icon" type="image/webp" href="img/icons/apple_touch.webp"/>
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
</head>
<body>
<header>
	<nav id="boxGauche">
		<a draggable="false" href="<?php echo CONTENU_PAGE["nav"]["href"]?>">
			<?php echo CONTENU_PAGE["nav"]["valeur"] . PHP_EOL?>
		</a>
	</nav>
	<div id="boxCentre">
		<img draggable="false"
			onClick="window.scrollTo(0,0);"
			src="<?php echo CHEMIN_DOSSIER_NAV?>meteor.svg"
			alt="Logo du site MeteoR" title="Recharger la page"
		/>
	</div>
	<div id="boxDroite" onclick="basculerAffichageMinMax()"
		title="Afficher <?php echo CONTENU_PAGE["minMax"]["titre"]
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
		<h1><?php echo CONTENU_PAGE["commun"]["typeMesures"]?></h1>
		<div title="<?php
			echo CONTENU_PAGE["commun"]["titreActu"] . " ";
			echo formatageDate($valeurActu[0])?>" id="jauge"
		>
		</div>
	</section>
	<section>
		<h1>Évolution dans le temps</h1>
		<div id="graphique"></div>
	</section>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.26.0/plotly-basic.min.js"
integrity="sha512-bsEo0AkGyf5peN2R7u2RtcbU4f0qH604l0+4OOHNA0lzLOmxDjLJYlyfg1AuhDkewl5swoDm/jU6EzKTKTZL0w=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.26.0/plotly-locale-fr.min.js"
integrity="sha512-nyAFXuhmcYPFCAawwaZOW22viMZW5Aw1jB7w84GbnbPqIz1SDHWGdQw17DB2BfU1jv4nnEdJgvolNINTjdSKMA=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.1.0/progressbar.min.js"
integrity="sha512-EZhmSl/hiKyEHklogkakFnSYa5mWsLmTC4ZfvVzhqYNLPbXKAXsjUYRf2O9OlzQN33H0xBVfGSEIUeqt9astHQ=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="js/accesDonnees.js"></script>
<script src="js/classes.js"></script>
<script src="js/graphique.js"></script>
<script src="js/mesures.js"></script>
<script src="js/entete.js"></script>
<script>
parametrerAfficherGraphique(<?php echo "\"" .
	CONTENU_PAGE["commun"]["nomColonne"] . "\", \"" .
	CONTENU_PAGE["commun"]["typeMesures"] . "\", \"" .
	CONTENU_PAGE["commun"]["unite"] . "\", " .
	$jaugeMinMax[0] . ", " .
	$jaugeMinMax[1]
?>);

jaugeMesure(<?php echo
	(((($valeurActu[1] - $jaugeMinMax[0]) * 100) /
		($jaugeMinMax[1] - $jaugeMinMax[0])) / 100) . ", " .
	$jaugeMinMax[0] . ", " .
	$jaugeMinMax[1] . ", \"" .
	CONTENU_PAGE["commun"]["unite"] . "\""
?>);

window.matchMedia("(prefers-color-scheme: light)").addEventListener("change",
() => {
	parametrerAfficherGraphique(<?php echo "\"" .
		CONTENU_PAGE["commun"]["nomColonne"] . "\", \"" .
		CONTENU_PAGE["commun"]["typeMesures"] . "\", \"" .
		CONTENU_PAGE["commun"]["unite"] . "\", " .
		$jaugeMinMax[0] . ", " .
		$jaugeMinMax[1]
	?>);

	jaugeMesure(<?php echo
		(((($valeurActu[1] - $jaugeMinMax[0]) * 100) /
			($jaugeMinMax[1] - $jaugeMinMax[0])) / 100) . ", " .
		$jaugeMinMax[0] . ", " .
		$jaugeMinMax[1] . ", \"" .
		CONTENU_PAGE["commun"]["unite"] . "\""
	?>);
});

activerHeaderReduit();
</script>
</body>
</html>