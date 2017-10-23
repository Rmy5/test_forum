<?php


class Message extends Com{

	private  $_message;
	private  $_nom;


	public function __construct(array $donnees){

		parent::__construct($donnees);
		$this->hydrate($donnees);
	}

	public function hydrate(array $donnees){

		foreach ($donnees as $key => $val) {
			$key = substr($key, 2);
			$method = 'set'.ucfirst($key);

			if (method_exists($this, $method)) $this->{$method}($val);
			
		}
	}


	public function getNom(){ return $this->_nom; }
	public function setNom($nom){ $this->_nom = $nom; }

	public function getContenu(){ return $this->_message; }
	public function setContenu($message){ $this->_message = $message; }
}









