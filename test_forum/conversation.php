<?php
require 'config.php';

$mManager = new MessageManager();

// Récupérer le nombre de messages de la conversation.
$countMessages = $mManager->getCountMessages($_GET['id']); 

// Calucl du nombre de pages à afficher en fonction du nombre de messages.
$pages = $countMessages / 20; 

// Arrondir le nombre de pages à l'entier supérieur.
$pages = ceil($pages); 

// Vérifier que le paramètre de tri de l'url est valide.
if ($_GET['sort'] == 'nom' || $_GET['sort'] == 'date' || $_GET['sort'] == 'id') {
	$sortValid = 1; // 
}

// S'il y a moins de 20 messages dans la conversation, on récupère tous les messages.
if ($countMessages < 20) {
	$messages = $mManager->getAllMessages($_GET['id'], $_GET['sort']);
}

// S'il y a plus de 20 messages, et que des paramètres de de pages valide ont été passés dan l'url...
elseif (isset($_GET['page']) && $countMessages > 20 && isset($sortValid)) {	 

	$offset = 0; // Valeur de l'offset de la requête.

	// En fonction de la page demandée on fait des requêtes avec l'offset correspondant.
	if ($_GET['page'] == 1) {
			$messages = $mManager->getPage($_GET['id'], 0, $_GET['sort']); // Requête pour la page 1.
		}
	for ($i=2; $i <= $pages; $i++) { 

		$nbMessagePage = 20 ;

		$offset += $nbMessagePage; // Augmentation de l'offset de 20 pour chaque itération (page).
		
		// Requête pour les autres pages.
		if ($_GET['page'] == $i){

			$messages = $mManager->getPage($_GET['id'], $offset, $_GET['sort']);
		}	
	}	
}


?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
        <link rel="stylesheet" type="text/css" href="assets/css/conversation.css">
	</head>
	<body>
		<?php
			// Affichage d'un message si une conversation ne contient aucun message.
			if (isset($messages) && count($messages) == 0) echo 'Cette conversation est vide pour le moment.';
			

			// Affichage d'une erreur 404 si la conversation n'existe pas ou s'il y une erreur de paramètres url.
			elseif (!isset($sortValid) || ($_GET['page'] > $pages || $_GET['page'] <= 0)) echo '<span id="err404">Erreur 404</span>';
			
			
			// Si la requête sur les messages a retourné des données.	
			elseif(!is_null($messages)) {

				// affichage du header de la table (avec liens en GET pour les tris).
				echo '<table><tr><th><a href="?id='.$_GET['id'].'&page='.$_GET['page'].'&sort=id">Id message</a></th><th><a href="?id='.$_GET['id'].'&page='.$_GET['page'].'&sort=date">Date du message</a></th><th>Heure du message</th><th><a href="?id='.$_GET['id'].'&page='.$_GET['page'].'&sort=nom">Nom Prénom</a></th><th>Message</th></tr>';

				// Afichage des des messages.
				foreach ($messages as  $val) {

					echo '<tr><td>'.$val->getId().'</td><td>'.$val->getDate().'</td><td>'.$val->getHeure().'</td><td>'.$val->getNom().'</td><td>'.$val->getContenu().'</td></tr>';
				}
				echo '</table>';			
			}


			// Affichage des liens "Page précédente/suivante" si le nombre de messages est supérieur à 20.
			if ($countMessages > 20) {

				// Affichage du lien "Page précédente" à partir de la page 2 et pour toutes les autres pages.
				if ($_GET['page'] > 1 && $_GET['page'] <= $pages && isset($sortValid)) {

					// Réafiche les autres paramètres présent dans l'url et retire 1 au paramètre de page.
					echo '<a href="?id='.$_GET['id'].'&page='.($_GET['page']-1).'&sort='.$_GET['sort'].'">Page précédente</a>  ';
				}

				// Affichage du lien "Page suivante" à partir de la page 1 et jusqu'à l'avant dernière page.
				if ($_GET['page'] < $pages && $_GET['page'] > 0 && isset($sortValid)) {

					// Réafiche les autres paramètres présent dans l'url et ajoute 1 au paramètre de page.
					echo ' <a href="?id='.$_GET['id'].'&page='.($_GET['page']+1).'&sort='.$_GET['sort'].'">Page suivante</a>  ';	
				}	
			}
			?>
			<a href=".">Retour aux conversations</a>
	</body>
</html>