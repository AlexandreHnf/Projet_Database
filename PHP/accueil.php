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
		?>

        <p> <a href="deconnexion.php">Se déconnecter</a></p>
    </body>
</html>
