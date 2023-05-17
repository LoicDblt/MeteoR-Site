<?php
include_once "BddGraphes.php";

$bddGraph = new BddGraphes();

if ($_POST["nomColonne"] != "undefined")
	echo $bddGraph->getValeursColonne($_POST["nomColonne"]);
else
	echo "Nom de colonne indéfini";