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

	public function actu($tempHumi)
	{
		try
		{
			$statement = $this->pdo->prepare("SELECT $tempHumi FROM meteor_donnees WHERE date_mesure IS (SELECT MAX(date_mesure) FROM meteor_donnees WHERE $tempHumi IS NOT NULL) AND $tempHumi IS NOT NULL");
			$statement->execute();
			return $statement->fetchAll()[0][0];
		}
		catch (Exception $exception)
		{
			return $exception->getMessage();
		}
	}
	public function minMax($minMaxOp, $minMaxTempHumi)
	{
		try
		{
			$statement = $this->pdo->prepare("SELECT date_mesure AS date, $minMaxOp($minMaxTempHumi) AS $minMaxTempHumi FROM meteor_donnees");
			$statement->execute();
			return $statement->fetchAll()[0];
		}
		catch (Exception $exception)
		{
			return $exception->getMessage();
		}
	}
}