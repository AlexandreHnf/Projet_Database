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
		
		<h1>Propositions d'achat en attente</h1>

		<?php

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $choice = $_POST['choice'];

			$errors = array(); // liste d'erreurs (messages)
			
			if (empty($choice)) {
				$errors[] = "Vous n'avez pas fait votre choix !";
			}

			if (count($errors) > 0) { // Si erreurs
				echo "Nous avons rencontré des problèmes avec vos informations :" . "<br>";
				foreach($errors as $e)
					echo '<p class="error">'.$e.'</p>';
			}

			else {

                if ($choice == "accepted") {
                    $choice = "True";
                }
                else { $choice = "False"; }

                // Si cet objet n'a pas deja été acheté 
                $req = $bdd->prepare('SELECT ItemID
                                    FROM PropositionAchat
                                    WHERE PropositionAchat.ItemID = ?
                                    AND PropositionAchat.accepted = "True"');

                $req->execute(array($_GET['itemID']));
                $prop1 = $req->fetch();
                $req->closeCursor();

                if ($prop1) { // Si deja accepté
                    $choice = 'False';
                }

                echo $choice;
                $req2 = $bdd->prepare('UPDATE PropositionAchat
                    SET accepted = :choix
                    WHERE ItemID = :itemid, Buyer = :buyer');
                $req2->execute(array(
                    'choix' => $choice,
                    'itemid' => $_GET['itemID'],
                    'buyer' => $_GET['buyer']
                    
                ));

                // if ($choice == "True") { // Update la table Objet
                //     $req3 = $bdd->prepare('UPDATE Objet
                //     SET Acheteur = :acheteur
                //     WHERE ItemID = :itemid');

                //     $req3->execute(array(
                //         'acheteur' => $_GET['buyer'],
                //         'itemid' => $_GET['itemID']
                //     ));
                // }

				// header('location: accueil.php');
				// exit;
			}
        }
        
        // Toutes les propositions d'achat qu'il a recu (On en prend une par une)
        $req = $bdd->prepare('SELECT PropositionAchat.ItemID, Buyer
                            FROM PropositionAchat, Utilisateur, Objet
                            WHERE PropositionAchat.ItemID = Objet.ItemID
                            AND Objet.SellerID = Utilisateur.UserID
                            AND PropositionAchat.accepted = "Attente"
                            AND Utilisateur.Pseudo = ?');

        $req->execute(array($_SESSION['pseudo']));
        $prop = $req->fetch();
        $req->closeCursor();
		
		if ($prop) {
            echo '<form class="form" action="gestion_propositions.php?itemID=' . $prop['ItemID'] . 
            '&buyer=' . $prop['Buyer'] . '" method="post">';
                echo"<p>";
                    // echo
                    echo "Accepter:<br>";
                    echo "<input type='radio' name='choice' value='accepted' /><br />";

                    echo "Refuser:<br>";
                    echo "<input type='radio' name='choice' value='refused' /><br />";

                    echo "<br />";
                    echo "<input type='submit' value='Confirmer' />";
                echo "</p>";

            echo "</form>";
        }

		// Retour 
        echo "<br>";
        echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>';
        ?>

   </body>
</html>
