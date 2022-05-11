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
			$this->error = $exception;
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
			return $exception;
		}
	}
	public function maxMin($maxMinOp, $maxMinTempHumi)
	{
		try
		{
			$statement = $this->pdo->prepare("SELECT strftime('%d-%m-%Y à %H:%M', date_mesure) AS date, $maxMinOp($maxMinTempHumi) AS $maxMinTempHumi FROM meteor_donnees");
			$statement->execute();
			return $statement->fetchAll()[0];
		}
		catch (Exception $exception)
		{
			return $exception;
		}
	}
}