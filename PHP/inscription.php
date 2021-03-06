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
       <title>Inscription</title>
   </head>

   <body>

		<h1>Inscription</h1>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $pseudo = $_POST['pseudo'];
            $password = $_POST['motdepasse'];
            $hashed = SHA1($password);
            $email = $_POST['email'];
            $desc = $_POST['description'];

            $errors = array(); // liste d'erreurs (messages)
            
            if (empty($pseudo) or empty($password) or empty($email)
                or empty($desc)) {
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
                echo "Nous avons rencontré des problèmes avec vos informations :" . "<br>";
                foreach($errors as $e)
                    echo '<p class="error">'.$e.'</p>';
            }

            else {
                $_SESSION['pseudo'] = $pseudo; // variable de session
                                               // donc accessible partout

                $req2 = $bdd->prepare('INSERT INTO Utilisateur(Pseudo, MotDePasse, 
                                                    AdresseMail, Description_user) 
                    VALUES(:pseudo, :mdp, :email, :desc)');
                $req2->execute(array(    
                    'pseudo' => $pseudo,
                    'mdp' => $hashed,
                    'email' => $email,
                    'desc' => $desc
                ));
                $req2->closeCursor();

                if (isSeller($pseudo)) {             // Variable de sess pour vendeurs
					$_SESSION['isSeller'] = true;
                }
                else {$_SESSION['isSeller'] = false; }

				if (isAdmin($pseudo)) {              // Variable de sess pour admins
					$_SESSION['isAdmin'] = true;
                }
                else {$_SESSION['isAdmin'] = false; }


                header('location: accueil.php');
                exit;
            }
        }

        ?>
		
		
		<form class='form' action="inscription.php" method="post">
			<p>
				Pseudo: <br /> 
                <input type="text" placeholder="pseudo" name="pseudo" /> <br /><br />
				Mot de passe: <br />
                <input type="password" placeholder="mot de passe" name="motdepasse" /> <br /><br />
                Adresse E-mail: <br />
                <input type="email" placeholder="exemple@a.be" name="email" /> <br /><br /><br />
                Votre description éventuelle : <br />
                <textarea name="description" rows="8" cols="45">
                </textarea> <br /><br />
				<input type="submit" value="S'inscrire" />
			</p>

		</form>

        <?php echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>'; ?>

   </body>
</html>
