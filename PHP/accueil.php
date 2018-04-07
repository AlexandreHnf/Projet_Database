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
        if ((isset($_POST['pseudo']) and isset($_POST['motdepasse'])) 
            and (!empty($_POST['pseudo']) and !empty($_POST['motdepasse']))) {

			$_SESSION['pseudo'] = $_POST['pseudo']; // variable de session
                                                    // donc accessible partout
            
        }

        else { // si le pseudo ou le mot de passe n'est pas valide
            header('location:connexion.php');
        }

        if (isset($_SESSION['pseudo'])) {
            echo '<p> Bonjour '. htmlspecialchars($_SESSION['pseudo']).'!</p>';
        }
		?>
        

		<p>Si tu veux changer de pseudo, <a href="connexion.php">clique ici</a> 
			pour revenir à la page de connexion <br />
		</p>

        <p> <a href="deconnexion.php">Se déconnecter</a></p>
    </body>
</html>
