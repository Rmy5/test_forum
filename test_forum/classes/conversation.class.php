<?php


class Conversation extends Com{


	private $_termine;
	private $_nbmessages;


	public function __construct(array $donnees){

		parent::__construct($donnees);
		$this->hydrate($donnees);
	}

	public function hydrate(array $donnees){

		foreach ($donnees as $key => $val) {
			$key = substr($key, 2);
			$method = 'set'.ucfirst($key);

			if (method_exists($this, $method)) {
				$this->{$method}($val);
			}
		}
	}


	// Retourne la valeur "opened" ou "closed" selon que la conversation est fermée ou ouverte.
	public function getTermine(){

		if ($this->_termine == 0) return 'opened';
		if ($this->_termine == 1) return 'closed';	
	}

	public function setTermine($termine){ $this->_termine = $termine; }
	
	public function getNbmessages(){ return $this->_nbmessages; }
	public function setNbmessages($nbmessages){ $this->_nbmessages = $nbmessages; }
	
}


















