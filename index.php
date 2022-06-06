<?php
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com");

include_once "classes/BddDonnees.php";
include_once "classes/BddGraphes.php";
$bddDonnees = new BddDonnees();
$bddGraph = new BddGraphes();

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
	<meta name="theme-color" content="#f9f9fb" media="(prefers-color-scheme: light)"/>
	<meta name="theme-color" content="#010101" media="(prefers-color-scheme: dark)"/>
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
<script
src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.12.1/plotly-basic.min.js"
integrity="sha512-1xh2+txa3PenvgKmdLkjsGdZ3gX+RmaAfETw+795FKOpW+DEgnL3GeRKeCXLQrbLNEzPWoR+J2jhMVQm9tYQGQ=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script
src="https://cdnjs.cloudflare.com/ajax/libs/plotly.js/2.12.1/plotly-locale-fr.min.js"
integrity="sha512-8i4gvdC9aB88kXdoZiv8knDmCNyCyOiR5JE9lKcYObBGTAs8qCkajAYSf+GpNVu+8GeEwX4254aQjJ8v0cejsw=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="js/index.js"></script>
<script>
const pointsAbscisse = <?php echo $bddGraph->getGraph("date_mesure")?>;
const pointsOrdonnee = <?php echo $bddGraph->getGraph($page['commun']['nomColonne'])?>;

parametrerAfficherGraphique(pointsAbscisse, pointsOrdonnee <?php echo
	", \"" . $page['commun']['typeDonnees'] . "\"" .
	", \"" . $page['commun']['unite'] . "\""
?>);

window.matchMedia("(prefers-color-scheme: light)").addEventListener("change",
() => {
	parametrerAfficherGraphique(pointsAbscisse, pointsOrdonnee <?php echo
		", \"" . $page['commun']['typeDonnees'] . "\"" .
		", \"" . $page['commun']['unite'] . "\""
	?>);
});
</script>
</body>
</html>