<?php 
	session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>devenir vendeur</title>
   </head>

   <body>
		<h2>Devenir Vendeur !</h2>

		<p>
			Vous souhaitez devenir vendeur ?
            Remplissez ce formulaire :
		</p>
		
		<?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $birthday = $_POST['date'];
            $adresse = $_POST['adresse'];

            $errors = array(); // liste d'erreurs (messages)
            
            if (empty($nom) or empty($prenom) or empty($birthday) or empty($adresse)) {
                $errors[] = "Vous n'avez pas complété tous les champs !";
            }

            if (count($errors) > 0) { // Si erreurs
                echo '<p Nous avons rencontré des problèmes avec vos informations : </p>';
                foreach($errors as $e)
                    echo '<p class="error">'.$e.'</p>';
            }

            else {
				include("database.php");

				$req = $bdd->prepare('SELECT UserID FROM Utilisateur WHERE Pseudo = ?');
				$req->execute(array($_SESSION['pseudo']));
				$donnees = $req->fetch();

                // $req2 = $bdd->prepare('INSERT INTO Vendeur(SellerID, Nom, Prenom, 
                       //DateNaissance, Adresse) 
                //     VALUES(:SellerID, :Nom, :Prenom, :DateNaissance, :Adresse)');
                // $req2->execute(array(    
                //     'SellerID' => donnees['UserID'],
                //     'Nom' => $nom,
                //     'Prenom' => $prenom,
                //     'DateNaissance' => $birthday,
                //     'Adresse' => $adresse,
                // ));

                //$req->closeCursor(); // Termine le traitement de la requête
                //$req2->closeCursor(); // Termine le traitement de la requête

                header('location: accueil.php');
                exit;
            }
        }

        ?>

		<form action="inscription_vendeur.php" method="post">
			<p>
				Nom:<br>
				<input type="text" name="nom" /> <br />
                Prénom:<br>
				<input type="text" name="prenom" /> <br />
				Date de naissance:<br>
				<input type="date" name="date" /> <br />
                Adresse:<br>
				<input type="text" name="adresse" /> <br />
				<input type="submit" value="Devenir vendeur" />
			</p>

		</form>

		<p> <a href="accueil.php"> Retour </a></p>

   </body>
</html>
