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

            $req = $bdd->query('UPDATE Objet inner join PropositionAchat 
            on Objet.ItemID = PropositionAchat.ItemID
            SET Objet.Acheteur = PropositionAchat.Buyer
            where PropositionAchat.accepted = "True"');

            // $req = $bdd->query('SELECT u.UserID, ItemID
            // FROM PropositionAchat, Utilisateur u
            // WHERE PropositionAchat.accepted = "True"
            // AND u.UserID = PropositionAchat.Buyer');

            // while ($buyers = $req->fetch()) {
            //     $req2 = $bdd->prepare('UPDATE Objet
            //     SET Acheteur = :acheteur
            //     WHERE ItemID = :itemid');

            //     $req2->execute(array(
            //     'acheteur' => $buyers['UserID'],
            //     'itemid' => $buyers['ItemID']

            //     ));
            // }

            $req->closeCursor();      
            
            header('location: accueil.php');

        ?>

   </body>
</html>
