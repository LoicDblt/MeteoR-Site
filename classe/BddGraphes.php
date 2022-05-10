<?php

class BddGraphes{
	public function __construct(){
		try{
			$this->pdo = new PDO("sqlite:bdd/graphs.db");
		}catch (PDOException $exception){
			$this->error = $exception;
		}
	}

	public function graphX(){
		try{
			$statement = $this->pdo->prepare("SELECT date_mesure FROM meteor_graphs WHERE date_mesure >= datetime('now', 'localtime', '-7 days', '-3 minutes')");
			$statement->execute();
			return json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
		}catch (Exception $exception){
			return $exception;
		}
	}
	public function graphY($tempHumi){
		try{
			$statement = $this->pdo->prepare("SELECT $tempHumi FROM meteor_graphs WHERE date_mesure >= datetime('now', 'localtime', '-7 days', '-3 minutes')");
			$statement->execute();
			return json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
		}catch (Exception $exception){
			return $exception;
		}
	}
}