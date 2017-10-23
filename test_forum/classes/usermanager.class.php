<?php


class UserManager{


	public function addUser($params = array()){

		$nom = htmlentities($_POST['nom']);
		$prenom = htmlentities($_POST['prenom']);
		$mail = htmlentities($_POST['mail']);
		$daten = htmlentities($_POST['date']);


		$add = SPDO::getInst()->callDatabase('INSERT INTO user(u_login, u_prenom, u_nom, u_date_naissance, u_date_inscription, u_rang_fk) VALUES (:mail, :prenom, :nom, :daten, NOW(), 3)', array(':mail'=>$mail, ':prenom'=>$prenom, ':nom'=>$nom, ':daten'=>$daten));
		return $add;
	}

	//Récupérer l'id de l'utilisateur loggé
	public function getUserId($params = array()){

		$nom = htmlentities($_SESSION['FORUM']['nom']);
		$prenom = htmlentities($_SESSION['FORUM']['prenom']);

		$res = SPDO::getInst()->callDatabase('SELECT u_id, u_nom, u_prenom FROM user WHERE u_nom = :nom AND u_prenom = :prenom ORDER BY u_id DESC LIMIT 1', array(':nom'=>$nom, ':prenom'=>$prenom));
		return $res;
	}

}




?>