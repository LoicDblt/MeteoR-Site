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

	public function getValeurActu($valeur) : float {
		try{
			$statement = $this->pdo->prepare("SELECT $valeur as valeur FROM meteor_donnees WHERE date_mesure IS (SELECT MAX(date_mesure) FROM meteor_donnees WHERE $valeur IS NOT NULL) AND $valeur IS NOT NULL");
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC)["valeur"];
		}
		catch (Exception $exception){
			return $exception->getMessage();
		}
	}
	public function getValeurMinMax($operation, $valeur) : array
	{
		try{
			$statement = $this->pdo->prepare("SELECT date_mesure AS date, $operation($valeur) AS $valeur FROM meteor_donnees");
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
		catch (Exception $exception){
			return $exception->getMessage();
		}
	}
}