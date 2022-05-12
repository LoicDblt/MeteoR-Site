<?php
class BddGraphes
{
	public function __construct()
	{
		try
		{
			$this->pdo = new PDO("sqlite:bdd/graphs.db");
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
			$statement = $this->pdo->prepare("SELECT date_mesure FROM meteor_graphs WHERE date_mesure >= datetime('now', 'localtime', '-30 days', '-1 hour')");
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
			$statement = $this->pdo->prepare("SELECT $tempHumi FROM meteor_graphs WHERE date_mesure >= datetime('now', 'localtime', '-30 days', '-1 hour')");
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