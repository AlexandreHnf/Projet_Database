<?php 
    session_start();  // On démarre la session
    include('database.php');
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>Vendre</title>
   </head>

   <body>
        <h2> Vendre </h2> 

        <?php
        $pseudo = $_SESSION['pseudo'];
        if (isset($pseudo)) {
            $req = $bdd->prepare('SELECT SellerID FROM Vendeur, Utilisateur
								WHERE Vendeur.SellerID = Utilisateur.UserID
                                AND Utilisateur.Pseudo = ?');
            $req->execute(array($pseudo));
            $donnees = $req->fetch();

            if (!$donnees) {
                echo "Vous n'êtes pas vendeur !" . "<br>";
                //echo "<a href=\"inscription_vendeur.php\"Devenir vendeur </a>";
                echo "<a href='inscription_vendeur.php'> Devenir vendeur ! </a>";
            }

            else {
                // vendre objets TODO
                echo "vendre maggle";
            }
            
        }
        ?>
   </body>
</html>