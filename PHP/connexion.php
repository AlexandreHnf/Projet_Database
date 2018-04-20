<?php 
	session_start();  // On démarre la session
	include("database.php");
?> 

<!DOCTYPE html>
<html>
   <head>
	   <meta charset="utf-8" />
	   <link rel="stylesheet" href="css/style.css">
       <title>Connexion</title>
   </head>

   <body>
		<h2>Connexion !</h2>

		<p>
			Ceci est la page de connexion du site Ebay.<br />
			Veuillez taper votre pseudo et votre mot de passe:
		</p>

		<?php

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$pseudo = $_POST['pseudo'];
			$mdp = $_POST['motdepasse'];
			$hashed_mdp = SHA1($mdp);

			$errors = array(); // liste d'erreurs (messages)
			
			if (empty($pseudo) or empty($mdp)) {
				$errors[] = "Vous n'avez pas complété tous les champs !";
			}

            $req = $bdd->prepare('SELECT Pseudo, MotDePasse FROM Utilisateur 
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
				header('location: accueil.php');
				exit;
			}
		}

		?>		
		
		<form action="connexion.php" method="post">
			<p>
				Pseudo:<br>
				<input type="text" placeholder="pseudo" name="pseudo" /> <br /><br/>
				Mot de passe:<br>

				<input type="password" placeholder="mot de passe" name="motdepasse" /> 
				<br /><br /><br />
				<input type="submit" value="Se connecter" />
			</p>

		</form>

		<?php echo "<br>"; ?>
		<?php echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>'; ?>

   </body>
</html>
