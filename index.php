<?php
// Sécurité
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com ajax.cloudflare.com");

// Accès à la base de données
include_once "classe/BaseDeDonnees.php";
$bdd = new BaseDeDonnees();
$valeurs = Array();

if (!$_GET){
	include_once "assets/temp.php";
	array_push($valeurs, array($bdd->maxMin("MAX", "max_temp")["date"], $bdd->maxMin("MAX", "max_temp")["max_temp"]));
	array_push($valeurs, array($bdd->maxMin("MIN", "min_temp")["date"], $bdd->maxMin("MIN", "min_temp")["min_temp"]));
}
elseif (isset($_GET["humi"])){
	include_once "assets/humi.php";
	array_push($valeurs, array($bdd->maxMin("MIN", "min_humi")["date"], $bdd->maxMin("MIN", "min_humi")["min_humi"]));
	array_push($valeurs, array($bdd->maxMin("MAX", "max_humi")["date"], $bdd->maxMin("MAX", "max_humi")["max_humi"]));
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
			<?php echo $bdd->actu($page["sectionActu"]["tempHumi"]) . $page["commun"]["type"]?>
		</p>
	</section>
	<section>
		<h1>Évolution dans le temps</h1>
		<div>
			<button onclick="change_graph('<?=$page['sectionGraphs']['buttonSrc']?>_24', this.id)">24 heures</button>
			<button id="actif" onclick="change_graph('<?=$page['sectionGraphs']['buttonSrc']?>_72', this.id)">3 jours</button>
			<button onclick="change_graph('<?=$page['sectionGraphs']['buttonSrc']?>_168', this.id)">1 semaine</button>
		</div>
		<div>
			<?php echo "<img src=\"img/graphs/graph_" . $page['sectionGraphs']['buttonSrc'] . "_24.webp?hash=" . filemtime("img/graphs/graph_" . $page['sectionGraphs']['buttonSrc'] . "_24.webp") . "\" alt=\"Evolution " . $page['sectionGraphs']['alt'] . " sur 24 heures\"/>\n"?>
			<?php echo "<img id=\"afficher\" src=\"img/graphs/graph_" . $page['sectionGraphs']['buttonSrc'] . "_72.webp?hash=" . filemtime("img/graphs/graph_" . $page['sectionGraphs']['buttonSrc'] . "_72.webp") . "\" alt=\"Evolution " . $page['sectionGraphs']['alt'] . " sur 3 jours\"/>\n"?>
			<?php echo "<img src=\"img/graphs/graph_" . $page['sectionGraphs']['buttonSrc'] . "_168.webp?hash=" . filemtime("img/graphs/graph_" . $page['sectionGraphs']['buttonSrc'] . "_168.webp") . "\" alt=\"Evolution " . $page['sectionGraphs']['alt'] . " sur 7 jours\"/>\n"?>
		</div>
	</section>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="javascript/images.js"></script>
</body>
</html>