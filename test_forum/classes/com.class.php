<?php

/*
*
*/
abstract class Com{

	private $_id;
	private $_date;
	private $_heure;


	public function __construct(array $donnees){

		$this->hydrate($donnees);
	}

	public function hydrate(array $donnees){

		foreach ($donnees as $key => $val) {
			$key = substr($key, 2);
			$method = 'set'.ucfirst($key);

			if (method_exists($this, $method)) $this->{$method}($val);	
		}
	}

	public function getId(){ return $this->_id; }
	public function setId($id){ $this->_id = $id; }

	public function getDate(){ return $this->_date; }
	public function setDate($date){ $this->_date = $date; }

	public function getHeure(){ return $this->_heure; }
	public function setHeure($heure){ $this->_heure = $heure; }
	
}



