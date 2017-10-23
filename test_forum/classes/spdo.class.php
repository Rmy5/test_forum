<?php


class SPDO{

	private static $_instance = NULL;
	private static $_pdo;


	private function __construct(){

		try{
			self::$_pdo = new PDO('mysql:host='._HOST_.';dbname='._DB_.';charset=utf8', _USER_, _PASS_, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		}
		catch(exception $e){ die('Erreur '.$e->getMessage()); }
	}



	public static function getInst(){

		if (is_null(self::$_instance)) self::$_instance = new self();
		
		return self::$_instance;
	}



	public function makeRequest($sql, $params = array()){

		$request = false;
		if(count($params) == 0)
		{
			$request = self::$_pdo->query($sql);
		}
		else
		{
			if($request = self::$_pdo->prepare($sql))
			{
				foreach ($params as $placeholder => $value)
				{
					if($request->bindValue($placeholder, $value) === false)
						return false;
				}
				if(!$request->execute())
				{
					return false;
				}
			}
		}
		
		return $request;
	}



	public function callDatabase($sql, $params = array(), $fetchStyle = PDO::FETCH_ASSOC, $fetchArg = NULL)
    {

		$request = self::makeRequest($sql, $params);

		if($request === false)
		{
			return false;
		}

		$data = is_null($fetchArg) ? $request->fetchAll($fetchStyle) : $request->fetchAll($fetchStyle, $fetchArg);
		$request->closeCursor();

		return $data;
	}




}











