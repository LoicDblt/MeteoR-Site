<?php
include_once "BddGraphes.php";

$bddGraph = new BddGraphes();
$tab = array();

if ($_POST["nomColonne"] != "undefined") {
	array_push($tab, $bddGraph->getValeursColonne("date_mesure"));
	array_push($tab, $bddGraph->getValeursColonne($_POST["nomColonne"]));
	echo json_encode($tab);
}
else
	echo "Nom de colonne indéfini";