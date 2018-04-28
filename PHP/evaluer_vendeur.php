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
       <title>Evaluer vendeur</title>
   </head>

   <body>

        <header>
            <?php include("menu.php"); ?>
        </header>

		<h1>Evaluer le vendeur</h1>

        <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $note = $_POST['note'];
            $com = $_POST['Description'];

            $errors = array(); // liste d'erreurs (messages)
            
            if (empty($note) or empty($com)) {
                $errors[] = "Vous n'avez pas complété tous les champs !";
            }

            if (count($errors) > 0) { // Si erreurs
                echo "Nous avons rencontré des problèmes avec vos informations :" . "<br>";
                foreach($errors as $e)
                    echo '<p class="error">'.$e.'</p>';
            }

            else {
                $req = $bdd->prepare('INSERT INTO Evaluation
                    (Buyer, Seller, Time, Rate, Commentaire)
                    VALUES (:buyer, :seller, :time, :rate, :com)');

                $date = new DateTime();
                $date = $date->format('Y-m-d');

                $req->execute(array(
                    'buyer' => $_GET['buyer'],
                    'seller' => $_GET['SellerID'],
                    'time' => $date,
                    'rate' => $note,
                    'com' => $com
                ));


                $req->closeCursor();

                header('location: accueil.php');
                exit;
            }
        }

        echo '<form class="form" action="evaluer_vendeur.php?buyer=' . $_GET['buyer'] 
        . '&SellerID=' . $_GET['SellerID'] . '" method="post">';
			echo"<p>";
                echo "Note (de 1 à 10): <br />";
                echo "<input type='number' name='note' min='1' max='10'><br/><br/>";
                echo "Commentaire: <br />";
                echo "<textarea name='Description' rows='8' cols='45'> </textarea> <br /><br />";

				echo "<input type='submit' value='Evaluer' />";
			echo "</p>";

		echo "</form>";

		// Retour
        echo "<br>";
        echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>';

        ?>

   </body>
</html>
