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
       <title>Proposition d'achat</title>
   </head>

   <body>

        <header>
            <?php include("menu.php"); ?>
        </header>
		
		<h1>Proposition d'achat </h1>

		<?php

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $price = $_POST['price'];

			$errors = array(); // liste d'erreurs (messages)
			
			if (empty($price)) {
				$errors[] = "Vous n'avez pas complété tous les champs !";
			}

			if (count($errors) > 0) { // Si erreurs
				echo "Nous avons rencontré des problèmes avec vos informations :" . "<br>";
				foreach($errors as $e)
					echo '<p class="error">'.$e.'</p>';
			}

			else {
                // On récupère l'id de l'acheteur
                $req = $bdd->prepare('SELECT UserID FROM Utilisateur 
                					  WHERE Pseudo = ?');
                $req->execute(array($_SESSION['pseudo']));
                $id = $req->fetch();
                
                // insertion dans la table proposition d'achat
                $req1 = $bdd->prepare('INSERT INTO PropositionAchat
                    (ItemID, Time, Buyer, price, accepted)
                    VALUES (:ItemID, :Time, :Buyer, :price, :accepted)')

                $Time = new DateTime();
                $Time = $Time->format('Y-m-d');

                $req1->execute(array(
                    'ItemID' => $_POST['ItemID'],
                    'Time' => $Time,
                    'Buyer' => $id,
                    'price' => $price,
                    'accepted' => NULL
                ));

                $req1->closeCursor();

				header('location: accueil.php');
				exit;
			}
		}
		
		
        echo '<form class="form" action="proposition_achat.php?page=' . $_GET['page'] . '&ItemID=' .
        $_GET['ItemID'] . '" method="post">';
			echo"<p>";
                echo "Prix proposé:<br>";
                echo "<input type='number' step='any' placeholder='prix' name='price' /> <br /><br/>";

				echo "<br />";
				echo "<input type='submit' value='Valider' />";
			echo "</p>";

		echo "</form>";

		// Pour retourner a l'accueil 
        echo "<br>";
        echo '<a href="liste_objets.php?page=' . $_GET['page'] . '&ItemID=' .
            $_GET['ItemID'] . '">
            <button class="button button1">Retour</button></a> ' . '<br><br>';
        ?>

   </body>
</html>
