<?php
class BddDonnees{
	public function __construct(){
		try{
			$this->pdo = new PDO("sqlite:bdd/donnees.db");
		}
		catch (PDOException $exception){
			$this->error = $exception->getMessage();
		}
	}

	public function getValeurActu($nomColonne) : float {
		try{
			$statement = $this->pdo->prepare(
				"SELECT $nomColonne as valeur
				FROM meteor_donnees
				WHERE $nomColonne IS NOT NULL
				ORDER BY date_mesure DESC LIMIT 1"
			);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC)["valeur"];
		}
		catch (Exception $exception){
			return $exception->getMessage();
		}
	}
	public function getValeurMinMax($operationMinMax, $nomColonne) : array {
		try{
			$statement = $this->pdo->prepare(
				"SELECT
					date_mesure,
					$operationMinMax($nomColonne) AS $nomColonne
				FROM meteor_donnees"
			);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_NUM);
		}
		catch (Exception $exception){
			return $exception->getMessage();
		}
	}
}