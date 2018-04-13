<?php 
    session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>main</title>
   </head>

   <body>
        <h2>Welcome ! Ebay du bled </h2>
        <?php include("database.php");

		$reponse = $bdd->query('SELECT * FROM Utilisateur');

		// On affiche chaque entrée une à une
		$donnees = $reponse->fetch();
		?>
		<p>
		<strong>Pseudo</strong> : <?php echo $donnees['Pseudo']; ?><br />
		</p>

        <?php
        $pseudo = "AWeRTaZzz";
        $req = $bdd->prepare('SELECT Pseudo, MotDePasse FROM Utilisateur WHERE Pseudo = ?');
        $req->execute(array($pseudo));
        $donnees2 = $req->fetch();

        if ($donnees2) {
            echo "yeah";
        }

        $req->closeCursor(); // Termine le traitement de la requête
        ?>
        <p>
		<strong>Pseudo</strong> : <?php echo $donnees2['Pseudo']; ?><br />
		</p>  

        <p> <a href="inscription.php"> S'inscrire </a></p>
        <p> <a href="connexion.php"> Se connecter </a></p>

   </body>
</html>
