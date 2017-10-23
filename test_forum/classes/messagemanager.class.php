<?php


class MessageManager{



	public function getAllMessages($id, $sort){

	
		$id = htmlentities($id);

		// Vérifier l'existence d'un conversation avec un COUNT sur la table, requête avec SPDO (statique).
		$count = SPDO::getInst()->callDatabase('SELECT COUNT(c_id) as test FROM conversation WHERE c_id = :id', array(':id'=>$id));

		// Si la conversation n'existe pas on retourne 0.
		if (($count[0]['test'] == 0) !== false ) {
			return 0;
		}

		// Sinon on récupère tous les messages(en formattant la date).
		else{
		
			$messages = array();

			if ($sort == 'date' || $sort == 'nom' || $sort == 'id') { // Vérification du paramètre que l'on va concaténer. FAIRE AVEC UN SWITCH et ne pas assigner DIRECTEMENT!!!

				$res = SPDO::getInst()->callDatabase("SELECT m.m_id, m_contenu, DATE_FORMAT(m_date, '%d/%m/%Y') AS m_date, DATE_FORMAT(m_date, '%H:%i:%s') AS m_heure, CONCAT_WS(' ',u.u_nom, u.u_prenom) AS m_nom FROM message m INNER JOIN user u ON m.m_auteur_fk = u.u_id INNER JOIN conversation c ON m_conversation_fk = c.c_id WHERE c.c_id = :id ORDER BY m_".$sort."", array(':id'=>$id)); // $sort MAL SECURISE !!!!!!!
			}

			// On Créé des messages/objets avec les données récupérées.
			foreach ($res as $val) {
				$messages[] = new Message($val);
			}
			return $messages;
		
		}	
	}

	public function getCountMessages($id){

		// Récupérer le nombre de messages (pour calculer le nombre pages à afficher, notamment)
		$result = SPDO::getInst()->callDatabase('SELECT COUNT(m_id) as count FROM message m INNER JOIN conversation c ON m_conversation_fk = c.c_id WHERE c.c_id = :id', array(':id'=> $id));

		$result = $result[0]['count'];
		return $result;
	}


	// Affichage une page de messages d'une conversation en fonction d'un offset donné, d'un id, et d'un type de tri.
	public function getPage($id, $offset, $sort){
		
		$id = htmlentities($id);

		$messages = array();

		// Offset: issu du calcul en fonction du numéro de la page. Sort: type de tri passé en paramètre url.
		// Vérification des paramètres que l'on va concaténer dans la requête.
		if (($sort == 'date' || $sort == 'nom' || $sort == 'id') && is_int($offset)) { 

			$res1 = SPDO::getInst()->callDatabase("SELECT m_id, m_contenu, DATE_FORMAT(m_date, '%d/%m/%Y') AS z_date, DATE_FORMAT(m_date, '%H:%i:%s') AS m_heure, CONCAT_WS(' ' ,u.u_nom, u.u_prenom) AS m_nom FROM message m LEFT JOIN user u ON m.m_auteur_fk = u.u_id LEFT JOIN conversation c ON m_conversation_fk = c.c_id WHERE c.c_id = :id ORDER BY m_".$sort." ASC LIMIT 20 OFFSET ".$offset."", array(':id'=> $id));
		}
		
		foreach ($res1 as $val) {
			$messages[] = new Message($val);
		}
		return $messages;
	}

	public function addMessage($params = array()){


		$conversation = htmlentities($_POST['conversation']); // Id de la conversation
		$message = htmlentities($_POST['message']); // Contenu du message
		$id = htmlentities($_SESSION['FORUM']['id']); // Id du forum
		
		// Ajout d'un message
		$add = SPDO::getInst()->callDatabase('INSERT INTO message(m_contenu, m_date, m_auteur_fk, m_conversation_fk) VALUES (:message, NOW(), :id, :conversation)', array(':message'=>$message, ':id'=>$id, ':conversation'=>$conversation));
		return $add;
	}
}

































	