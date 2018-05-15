<?php 
	session_start();  // On démarre la session
	include("database.php");
	include("function.php");
?> 

<!DOCTYPE html>
<html>
   <head>
	   <meta charset="utf-8" />
	   <link rel="stylesheet" href="css/style.css">
       <title>Connexion</title>
   </head>

   <body>
		
		<h1>Connexion </h1>

		<?php

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$pseudo = $_POST['pseudo'];
			$mdp = $_POST['motdepasse'];
			$hashed_mdp = SHA1($mdp);

			$errors = array(); // liste d'erreurs (messages)
			
			if (empty($pseudo) or empty($mdp)) {
				$errors[] = "Vous n'avez pas complété tous les champs !";
			}

            $req = $bdd->prepare('SELECT Pseudo, MotDePasse, UserID FROM Utilisateur 
								WHERE Pseudo = ? AND MotDePasse = ?');
            $req->execute(array($pseudo, $hashed_mdp)); // avec mdp hashé
            $donnees = $req->fetch();

            
            if (!$donnees) { // Si pas dans la db
                $errors[] = "Vous n'avez pas encore de compte ou vos données sont erronées !";
            }

            $req->closeCursor(); // Termine le traitement de la requête

			if (count($errors) > 0) { // Si erreurs
				echo "Nous avons rencontré des problèmes avec vos informations :" . "<br>";
				foreach($errors as $e)
					echo '<p class="error">'.$e.'</p>';
			}

			else {
				$_SESSION['pseudo'] = $pseudo; // variable de session
														// donc accessible partout
				$_SESSION['id'] = $donnees['UserID'];
														
				if (isSeller($pseudo)) {             // Variable de sess pour vendeurs
					$_SESSION['isSeller'] = true;
				}
				else {$_SESSION['isSeller'] = false; }

				if (isAdmin($pseudo)) {              // Variable de sess pour admins
					$_SESSION['isAdmin'] = true;
				}
				else {$_SESSION['isAdmin'] = false; }

				//include('update_acheteurs.php');

				header('location: accueil.php');
				exit;
			}
		}

		?>		
		
		<form class='form' action="connexion.php" method="post">
			<p>
				Pseudo:<br>
				<input type="text" placeholder="pseudo" name="pseudo" /> <br /><br/>
				Mot de passe:<br>

				<input type="password" placeholder="mot de passe" name="motdepasse" /> 
				<br /><br /><br />
				<input type="submit" value="Se connecter" />
			</p>

		</form>

		<!-- Pour retourner a l'accueil  -->
		<?php echo "<br>"; ?>
		<?php echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>'; ?>

   </body>
</html>
