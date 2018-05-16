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
   </head>

   <body>

        <?php
            // Si cet objet n'a pas deja été acheté 
            $req = $bdd->query('SELECT u.Pseudo, ItemID
            FROM PropositionAchat, Utilisateur u
            WHERE PropositionAchat.accepted = "True"
            AND u.UserID = PropositionAchat.Buyer');

            while ($buyers = $req->fetch()) {
                $req2 = $bdd->prepare('UPDATE Objet
                SET Acheteur = :acheteur
                WHERE ItemID = :itemid');

                $req2->execute(array(
                'acheteur' => $buyers['Pseudo'],
                'itemid' => $buyers['ItemID']

                ));
            }

            $req->closeCursor();      
            
            header('location: accueil.php');

        ?>

   </body>
</html>
