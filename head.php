<?php
// Sécurité
header("X-Frame-Options: DENY");
header("Content-Security-Policy: base-uri 'self'; script-src 'self' 'unsafe-inline' cdnjs.cloudflare.com ajax.cloudflare.com");

// Accès à la base de données
include_once "classe/BaseDeDonnees.php";
$bdd = new BaseDeDonnees();
$valeurs = Array();

if (!$_GET || isset($_GET["temp"])){
	include "assets/temp.php";
	array_push($valeurs, array($bdd->maxMin("MAX", "max_temp")["date"], $bdd->maxMin("MAX", "max_temp")["max_temp"]));
	array_push($valeurs, array($bdd->maxMin("MIN", "min_temp")["date"], $bdd->maxMin("MIN", "min_temp")["min_temp"]));
}
elseif (isset($_GET["humi"])){
	include "assets/humi.php";
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
	<title>MeteoR</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="robots" content="noindex, nofollow"/>
	<meta name="color-scheme" content="light dark"/>
	<meta name="theme-color" content="#F9F9FB"/>
	<meta name="description" content="<?=$page["head"]["desc"]?>"/>
	<link rel="manifest" href="meteor.webmanifest"/>
	<link rel="icon" type="image/webp" href="img/meteor_favicon.webp"/>
	<link rel="stylesheet" type="text/css" href="style/style.css"/>
</head>

<?php
// Correction de l'affichage du body dans le code source
echo "";
?>