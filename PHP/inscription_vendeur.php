<?php 
    session_start();  // On démarre la session
    include("database.php");
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <link rel="stylesheet" href="css/style.css">
       <title>devenir vendeur</title>
   </head>

   <body>
        <header>
            <?php include("menu.php"); ?>
        </header>
        
		<h2>Devenir Vendeur !</h2>
		
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

            $verif = $bdd->prepare('SELECT SellerID FROM Vendeur WHERE Nom = ? AND
                                    Prenom = ?');
            $verif->execute(array($nom, $prenom));
            $res = $verif->fetch();

            if ($res) { // Si deja dans la database
                $errors[] = "Vous êtes deja vendeur !";
            }

            if (count($errors) > 0) { // Si erreurs
                echo "Nous avons rencontré des problèmes avec vos informations :" . "<br>";
                foreach($errors as $e)
                    echo '<p class="error">'.$e.'</p>';
            }

            else {

				$req = $bdd->prepare('SELECT UserID FROM Utilisateur WHERE Pseudo = ?');
				$req->execute(array($_SESSION['pseudo']));
				$donnees = $req->fetch();

                $req2 = $bdd->prepare('INSERT INTO Vendeur(SellerID, Nom, Prenom, 
                       DateNaissance, Adresse) 
                    VALUES(:SellerID, :Nom, :Prenom, :DateNaissance, :Adresse)');
                $req2->execute(array(    
                    'SellerID' => $donnees['UserID'],
                    'Nom' => $nom,
                    'Prenom' => $prenom,
                    'DateNaissance' => $birthday,
                    'Adresse' => $adresse,
                ));

                $req->closeCursor(); // Termine le traitement de la requête
                $req2->closeCursor(); // Termine le traitement de la requête

                header('location: accueil.php');
                exit;
            }
        }

        ?>

		<form class='form' action="inscription_vendeur.php" method="post">
			<p>
				Nom:<br>
				<input type="text" placeholder="Nom" name="nom" /> <br /><br />
                Prénom:<br>
				<input type="text" placeholder="Prenom" name="prenom" /> <br /><br />
				Date de naissance:<br>
				<input type="date" name="date" /> <br /><br />
                Adresse:<br>
                <input type="text" placeholder="rue n° ville" name="adresse" /> 
                <br /><br /><br />
				<input type="submit" value="Devenir vendeur" />
			</p>

		</form>

		<?php echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>'; ?>

   </body>
</html>
