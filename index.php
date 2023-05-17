<?php
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com");

include_once "classes/BddDonnees.php";
$bddDonnees = new BddDonnees();

$valeursMinMax = Array();
$cheminDossierImgNav = "img/nav/";

if ($_SERVER["REQUEST_URI"] == "/"){
	include_once "assets/temperature.php";
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MAX", "max_temp"));
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MIN", "min_temp"));
}
elseif ($_SERVER["REQUEST_URI"] == "/humidite"){
	include_once "assets/humidite.php";
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MIN", "min_humi"));
	array_push($valeursMinMax, $bddDonnees->getValeurMinMax("MAX", "max_humi"));
}

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

function formatageValeur($valeur) : string {
	return number_format($valeur, 1);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8"/>
	<title><?php echo $page['commun']['typeDonnees']?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)"/>
	<meta name="description" content="<?php echo $page['head']['desc']?>"/>
	<link rel="manifest" href="meteor.webmanifest"/>
	<link rel="icon" type="image/webp" href="img/icons/favicon.webp"/>
	<link rel="apple-touch-icon" type="image/webp" href="img/icons/apple_touch.webp"/>
	<link rel="stylesheet" type="text/css" href="style/index.css"/>
</head>
<body>
<header>
	<nav>
		<a draggable="false" href="<?php echo $page['nav']['href']?>">
			<?php echo $page['nav']['valeur'] . PHP_EOL?>
		</a>
	</nav>
	<div id="boxCentre">
		<img draggable="false" src="<?php echo
			$cheminDossierImgNav?>meteor.svg" alt="Logo du site MeteoR"/>
	</div>
	<div id="boxDroite" onclick=
	"inverserAffichageMinMax(`<?php echo $page['minMax']['titre']?>`)"
	title="Afficher <?php echo $page['minMax']['titre']?>">
		<div>
			<img draggable="false" src=<?php echo
				"\"" . $cheminDossierImgNav .
				$page['minMax']['divGauche']['img']?>.svg" alt=<?php echo
				"\"" . ucwords($page['minMax']['divGauche']['img'])
			?>"/>
			<img draggable="false" src=<?php echo
				"\"" . $cheminDossierImgNav .
				$page['minMax']['divDroite']['img']?>.svg" alt=<?php echo
				"\"" . ucwords($page['minMax']['divDroite']['img'])
			?>"/>
		</div>
		<div id="valeursMinMax">
			<p title="<?php
				// 2 echo séparés, sinon ordre d'affichage incorrect !
				echo $page['minMax']['divGauche']['titre'] . " ";
				echo formatageDate($valeursMinMax[0][0]);
				?>">
				<?php echo formatageValeur($valeursMinMax[0][1]) .
					$page['commun']['unite'] . PHP_EOL?>
			</p>
			<p title="<?php
				echo $page['minMax']['divDroite']['titre'] . " ";
				echo formatageDate($valeursMinMax[1][0])
				?>">
				<?php echo formatageValeur($valeursMinMax[1][1]) .
					$page['commun']['unite'] . PHP_EOL?>
			</p>
		</div>
	</div>
</header>
<section>
	<section>
		<h1><?php echo $page['commun']['typeDonnees']?></h1>
		<p>
			<?php echo
				formatageValeur(
					$bddDonnees->getValeurActu($page['commun']['nomColonne'])
				) . $page['commun']['unite'] . PHP_EOL
			?>
		</p>
	</section>
	<section>
		<h1>Évolution dans le temps</h1>
		<div id="graphique">
		</div>
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
<script src="js/index.js"></script>
<script>
parametrerAfficherGraphique(<?php echo "\"" .
	$page['commun']['nomColonne'] . "\", \"" .
	$page['commun']['typeDonnees'] . "\", \"" .
	$page['commun']['unite'] . "\""
?>);

window.matchMedia("(prefers-color-scheme: light)").addEventListener("change",
() => {
	parametrerAfficherGraphique(<?php echo "\"" .
		$page['commun']['nomColonne'] . "\", \"" .
		$page['commun']['typeDonnees'] . "\", \"" .
		$page['commun']['unite'] . "\""
	?>);
});
</script>
</body>
</html>