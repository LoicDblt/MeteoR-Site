<?php
include_once "BddMoyennes.php";

$bddMoyennes = new BddMoyennes();
$tab = array();

if ($_POST["nomColonne"] != "undefined") {
	array_push($tab, $bddMoyennes->getValeursColonne("date"));
	array_push($tab, $bddMoyennes->getValeursColonne($_POST["nomColonne"]));
	echo json_encode($tab);
}
else {
	echo "Nom de colonne indéfini";
}