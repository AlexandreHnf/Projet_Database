<?php 
    session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Accueil</title>
    </head>

    <body>
        <h2>Accueil</h2>

        <?php

        if (isset($_SESSION['pseudo'])) {
            echo '<p> Bonjour '. htmlspecialchars($_SESSION['pseudo']).'!</p>';
        }

        include("database.php");
        $req3 = $bdd->prepare('SELECT Pseudo, AdresseMail FROM Utilisateur WHERE Pseudo = ?');
        $req3->execute(array('a'));
        $donnees3 = $req3->fetch();

        if ($donnees3) {
            echo "mail de a: " . $donnees3['AdresseMail'];
        }
		?>

        <p> <a href="inscription_vendeur.php">Devenir vendeur !</a></p>
        <p> <a href="deconnexion.php">Se déconnecter</a></p>
    </body>
</html>
