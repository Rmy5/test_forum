<?php
require 'config.php';

session_start();
if (isset($_SESSION) && isset($_GET['logout'])) {
	unset($_SESSION['FORUM']);
	header('location: .');
}


// Instances des managers.
$cManager = new ConversationManager();
$mManager = new messageManager();
$uManager = new UserManager();


// On vérifie dès le début si le formulaire de message à été soumis, on ajoute le message et on créé un message.
if (isset($_POST['sub']) && isset($_SESSION['FORUM']['nom'])) {

	$mManager->addMessage($_POST) ? $msg = 'Message ajouté' : $msg = 'erreur message';
}

// Récupérer toutes les conversations.
$conversations = $cManager->getAllConversations();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Forum</title>
		<link rel="stylesheet" type="text/css" href="assets/css/index.css">
	</head>
	<body>
		<table id="msg">
			<tr>
				<th>ID de la conservation</th>
				<th>Date de la conversation</th>
				<th>Heure de la conversation</th>
				<th>Nombre de messages</th>
				<th>...</th>
			</tr>
			<?php
				// Affichage des conversations.
				foreach ($conversations as  $val) {
					echo '<tr class="'.$val->getTermine().'"> <td>'.$val->getId().'</td><td>'.$val->getDate().'</td><td>'.$val->getHeure().'</td><td>'.$val->getNbMessages().'</td><td><a href="conversation.php?id='.$val->getId().'&page=1&sort=date">voir</a></td></tr>';
				}
			?>
		</table>
		<form action="" method="POST" name=formMessage>
			<h5>Ajouter un message dans une conversation :</h5>
			<label>Choisissez une conversation : </label>
			<select name="conversation">
				<?php

					// Affichage d'options de selection pour les conversations "ouvertes".
					foreach ($conversations as $value) {

						if ($value->getTermine() == 'opened') echo '<option>'.$value->getId().'</option>';		
					}
				?>
			</select><br>
			<textarea placeholder="Entrez un message" name="message"></textarea><br>
			<input type="submit" name="sub">
		</form>
		<?php
		if (isset($msg) && !is_null($msg)) echo $msg; //Affichage d'un message ou d'une erreur sur l'insertion message.
			
			// Si l'utilisateur n'est pas connecté et qu'il à envoyé le formulaire, on affiche un formulaire d'inscription/connexion.
			if (isset($_POST['sub']) && !isset($_SESSION['FORUM']['nom'])) {?>
				<div id="login">
					<form action="" method="POST" id="sign" name="formSign">
						<table>
							<tr><td class="l">Nom</td><td><input type="text" name="nom" pattern="[a-zA-Z0-9._-]{3,15}"></td></tr>
							<tr><td class="l">Prénom</td><td><input type="text" name="prenom" pattern="[a-zA-Z0-9._-]{3,15}"></td></tr>
							<tr><td class="l">Mail</td><td><input type="text" name="mail" ></td></tr>
							<tr><td class="l">Date de naissance</td><td><input type="date" name="date"></td></tr>
						</table>
						<input type="submit" name="sub2">
					</form>
				</div';
			<?php }

			// Si l'utilisateur à envoyé le formulaire d'inscription on le traite, est on ajoute l'utlisateur dans la base.
			if (isset($_POST['sub2'])) {

				if ($res = $uManager->addUser($_POST)) {
					
					$_SESSION['FORUM']['nom'] = $_POST['nom'];
					$_SESSION['FORUM']['prenom'] = $_POST['prenom'];

					// On récupère l'id de l'user ajouté par l'auto-increment de la base et on le garde en session.
					if ($id = $uManager->getUserId($_SESSION)) $_SESSION['FORUM']['id'] = $id[0]['u_id'];	
				}
			}
			// Affichage du la session en cours et lien de deconnexion.
			if (isset($_SESSION['FORUM']['nom'])) {

				echo '<div id="logged">Vous êtes connecté en tant que : '.$_SESSION['FORUM']['prenom'].' '.$_SESSION['FORUM']['nom'].' (<a href="?logout=1">deconnexion</a>)';
			}
		?>
	</body>
</html>

