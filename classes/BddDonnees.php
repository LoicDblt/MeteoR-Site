<?php
class BddDonnees
{
	public function __construct()
	{
		try
		{
			$this->pdo = new PDO("sqlite:bdd/donnees.db");
		}
		catch (PDOException $exception)
		{
			$this->error = $exception->getMessage();
		}
	}

	public function getActu($valeurTempHumi) : float
	{
		try
		{
			$statement = $this->pdo->prepare("SELECT $valeurTempHumi as valeur FROM meteor_donnees WHERE date_mesure IS (SELECT MAX(date_mesure) FROM meteor_donnees WHERE $valeurTempHumi IS NOT NULL) AND $valeurTempHumi IS NOT NULL");
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC)["valeur"];
		}
		catch (Exception $exception)
		{
			return $exception->getMessage();
		}
	}
	public function getMinMax($operationMinMax, $valeurTempHumi) : array
	{
		try
		{
			$statement = $this->pdo->prepare("SELECT date_mesure AS date, $operationMinMax($valeurTempHumi) AS $valeurTempHumi FROM meteor_donnees");
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
		catch (Exception $exception)
		{
			return $exception->getMessage();
		}
	}
}