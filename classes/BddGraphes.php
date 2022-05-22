<?php
class BddGraphes
{
	protected $calcDate;

	public function __construct()
	{
		try
		{
			$this->pdo = new PDO("sqlite:bdd/graphs.db");

			// Génération de la date minimale pour les graphiques
			date_default_timezone_set("Europe/Paris");
			$date = new DateTime();
			$date->modify("last day of previous month");
			$this->calcDate = date("Y-m-d H:i:s", strtotime("-" . $date->format("d") . " days, -1 hours, -3 minutes"));
		}
		catch (PDOException $exception)
		{
			$this->error = $exception->getMessage();
		}
	}

	public function graphX()
	{
		try
		{
			$statement = $this->pdo->prepare("SELECT date_mesure FROM meteor_graphs WHERE date_mesure >= :calcDate");
			$statement->bindParam(":calcDate", $this->calcDate, PDO::PARAM_STR);
			$statement->execute();
			$array = array();
			foreach ($statement as $value)
			{
				array_push($array, $value[0]);
			}
			return json_encode($array);
		}
		catch (Exception $exception)
		{
			return $exception->getMessage();
		}
	}
	public function graphY($tempHumi)
	{
		try
		{
			$statement = $this->pdo->prepare("SELECT $tempHumi FROM meteor_graphs WHERE date_mesure >= :calcDate");
			$statement->bindParam(":calcDate", $this->calcDate, PDO::PARAM_STR);
			$statement->execute();
			$array = array();
			foreach ($statement as $value)
			{
				array_push($array, $value[0]);
			}
			return json_encode($array);
		}
		catch (Exception $exception)
		{
			return $exception->getMessage();
		}
	}
}