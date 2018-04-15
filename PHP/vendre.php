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

        <p> <a href="inscription.php"> S'inscrire </a></p>
        <p> <a href="connexion.php"> Se connecter </a></p>

        <?php
        $pseudo = $_SESSION['pseudo'];
        if (isset($pseudo)) {
            $req = $bdd->prepare('SELECT SellerID FROM Vendeur 
								WHERE Pseudo = ?'); // A MODIFIER
            $req->execute(array($pseudo));
            $donnees = $req->fetch();

            if (!$donnes) {
                echo "Vous n'êtes pas vendeur ! \n";
                echo "<a href=\"inscription_vendeur.php\"Devenir vendeur </a>";
            }

            else {
                // vendre objets TODO
            }
            
        }
        ?>
   </body>
</html>