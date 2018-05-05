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
				$errors[] = "Vous n'avez pas entré de prix !";
            }
            
            $req = $bdd->prepare('SELECT PrixMin FROM Objet 
                					  WHERE ItemID =  ?');
            $req->execute(array($_GET['ItemID']));
            $price_min = $req->fetch();

            echo $price_min['price'];

            if ($price > $price_min) {
                $errors[] = "Veuillez entrer un prix supérieur ou égal au prix minimum
                demandé par le vendeur (" . $price_min['price'] . " €)";
            }

			if (count($errors) > 0) { // Si erreurs
				echo "Nous avons rencontré des problèmes avec vos informations :" . "<br>";
				foreach($errors as $e)
					echo '<p class="error">'.$e.'</p>';
			}

			else {
                // On récupère l'id de l'acheteur
                $req1 = $bdd->prepare('SELECT UserID FROM Utilisateur 
                					  WHERE Pseudo = ?');
                $req1->execute(array($_SESSION['pseudo']));
                $id = $req1->fetch();
                // echo $id['UserID'];
                //insertion dans la table proposition d'achat
                $req2 = $bdd->prepare('INSERT INTO PropositionAchat
                    (ItemID, Time, Buyer, price, accepted)
                    VALUES (:itemid, :DateProp, :Buyer, :price, :accepted)');

                $date = new DateTime();
                $date = $date->format('Y-m-d');

                $req2->execute(array(
                    'itemid' => $_GET['ItemID'],
                    'DateProp' => $date,
                    'Buyer' => $id['UserID'],
                    'price' => $price,
                    'accepted' => "Attente"
                ));

                $req1->closeCursor();
                $req2->closeCursor();

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

		// Retour
        echo "<br>";
        echo '<a href="liste_objets.php?page=' . $_GET['page'] . '&ItemID=' .
            $_GET['ItemID'] . '">
            <button class="button button1">Retour</button></a> ' . '<br><br>';
        ?>

   </body>
</html>
