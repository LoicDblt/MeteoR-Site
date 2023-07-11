<?php
class BddMesures{
	private $pdo;

	public function __construct() {
		try {
			$this->pdo = new PDO("sqlite:bdd/mesures.db");
		}
		catch (PDOException $exception) {
			$this->error = $exception->getMessage();
		}
	}


	/**
	 * Récupère la valeur actuelle
	 * 
	 * @param string $nomColonne Nom de la colonne à traiter (pour le type)
	 *
	 * @return array Tableau de la mesure actuelle, avec la date de la prise
	 */
	public function getValeurActu($nomColonne) : array {
		try {
			$statement = $this->pdo->prepare(
				"SELECT
					date,
					$nomColonne as valeur
				FROM mesures
				WHERE $nomColonne IS NOT NULL
				ORDER BY date DESC LIMIT 1"
			);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_NUM);
		}
		catch (Exception $exception) {
			return $exception->getMessage();
		}
	}


	/**
	 * Récupère la valeur min et max
	 * 
	 * @param string $operationMinMax "MIN" ou "MAX"
	 * @param string $nomColonne Nom de la colonne à traiter (pour le type)
	 *
	 * @return array Tableau de la mesure min ou max, avec la date de la prise
	 */
	public function getValeurMinMax($operationMinMax, $nomColonne) : array {
		try {
			$statement = $this->pdo->prepare(
				"SELECT
					date,
					$operationMinMax($nomColonne) AS $nomColonne
				FROM bornes"
			);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_NUM);
		}
		catch (Exception $exception) {
			return $exception->getMessage();
		}
	}
}