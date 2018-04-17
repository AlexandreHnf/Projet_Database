<?php 
    session_start();  // On démarre la session
    include("database.php");
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>Inscription</title>
   </head>

   <body>
		<h2>Inscription !</h2>

		<p>
			Ceci est la page d'inscription du site Ebay.<br />
			Veuillez compléter le formulaire d'inscription:
		</p>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $pseudo = $_POST['pseudo'];
            $password = $_POST['motdepasse'];
            $hashed = SHA1($password);
            $email = $_POST['email'];

            $errors = array(); // liste d'erreurs (messages)
            
            if (empty($pseudo) or empty($password) or empty($email)) {
                $errors[] = "Vous n'avez pas complété tous les champs !";
            }

            // Check avec la database
            $req = $bdd->prepare('SELECT Pseudo FROM Utilisateur WHERE Pseudo = ?');
            $req->execute(array($pseudo));
            $donnees = $req->fetch();
            
            if ($donnees) { // Si deja dans la db
                $errors[] = "Pseudo deja pris !";
            }

            $req->closeCursor(); // Termine le traitement de la requête

            if (count($errors) > 0) { // Si erreurs
                echo '<p Nous avons rencontré des problèmes avec vos informations : </p>';
                foreach($errors as $e)
                    echo '<p class="error">'.$e.'</p>';
            }

            else {
                $_SESSION['pseudo'] = $pseudo; // variable de session
                                               // donc accessible partout

                $req2 = $bdd->prepare('INSERT INTO Utilisateur(Pseudo, MotDePasse, AdresseMail) 
                    VALUES(:pseudo, :mdp, :email)');
                $req2->execute(array(    
                    'pseudo' => $pseudo,
                    'mdp' => $hashed,
                    'email' => $email
                ));


                header('location: accueil.php');
                exit;
            }
        }

        ?>
		
		
		<form action="inscription.php" method="post">
			<p>
				Pseudo: <br /> 
                <input type="text" name="pseudo" /> <br /> 
				Mot de passe: <br />
                <input type="password" name="motdepasse" /> <br />
                Adresse E-mail: <br />
                <input type="email" name="email" /> <br />
				<input type="submit" value="S'inscrire" />
			</p>

		</form>

        <p> <a href="main.php"> Retour </a></p>

   </body>
</html>
