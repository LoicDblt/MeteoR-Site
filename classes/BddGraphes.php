<?php
class BddGraphes{
	protected $dateCalculee;

	public function __construct(){
		try{
			$this->pdo = new PDO("sqlite:../bdd/graphs.db");

			// Génération de la date antérieur minimale de récupération
			// pour les graphiques
			date_default_timezone_set("Europe/Paris");
			$date = new DateTime();
			$date->modify("last day of previous month");
			$this->dateCalculee = date("Y-m-d H:i:s", strtotime("-" .
				$date->format("d") . " days, -1 hours, -3 minutes")
			);
		}
		catch (PDOException $exception){
			$this->error = $exception->getMessage();
		}
	}


	/**
	 * Récupère les valeurs d'une colonne
	 * @param string $nomColonne Nom de la colonne à récupérer
	 *
	 * @return string Valeurs de la colonne
	 */
	public function getValeursColonne($nomColonne) : string {
		try{
			$statement = $this->pdo->prepare(
				"SELECT $nomColonne
				FROM meteor_graphs
				WHERE date_mesure >= :dateCalculee"
			);
			$statement->bindParam(":dateCalculee", $this->dateCalculee,
				PDO::PARAM_STR);
			$statement->execute();
			$array = array();
			foreach ($statement as $valeur)
				array_push($array, $valeur[0]);

			return json_encode($array);
		}
		catch (Exception $exception){
			return $exception->getMessage();
		}
	}
}