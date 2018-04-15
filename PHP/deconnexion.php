<?php 
    session_start(); // On démarre la session
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Deconnexion</title>
        <meta charset="utf-8" />
    </head>

    <body>
        <h2>Deconnexion !</h2>

        <?php 
        if (isset($_SESSION['pseudo'])) {
            echo '<p> Au revoir '. htmlspecialchars($_SESSION['pseudo']).'!</p>';
        }
        ?>

        <p> Vous avez choisi de vous déconnecter. 

        <?php 
            session_destroy(); // on détruit la session
        ?>

        <p> <a href="main.php">Revenir au menu de base<a></p>
        
    </body>
</html>
