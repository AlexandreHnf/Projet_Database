<?php
    session_start();  // On démarre la session
    include('database.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css">
        <title>Supprimer un objet</title>
    </head>

    <body>
        <header>
          <?php include("menu.php"); ?>
        </header>
   

        <h1>Supprimer l'objet</h1>

        <?php

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['mdp'])) {

            $mdp = $_POST['mdp'];
            $hashed_mdp = SHA1($mdp);
            
            $req = $bdd->prepare('SELECT UserID FROM Utilisateur 
                                WHERE Pseudo = ? AND MotDePasse = ?');
            $req->execute(array($_SESSION['pseudo'], $hashed_mdp)); // avec mdp hashé
            $donnees = $req->fetch();

            if ($donnees) {
                //Supprimer les propositions d'achat liées
                $reqDelProp = $bdd->prepare('DELETE FROM PropositionAchat
                WHERE ItemID = ?');
                $reqDelProp->execute(array($_GET['ItemID']));
                $reqDelProp->closeCursor();                    

                //ok, on supprime l'objet
                $reqDelObj = $bdd->prepare('DELETE FROM Objet
                WHERE ItemID = ?');
                $reqDelObj->execute(array($_GET['ItemID']));
                $reqDelObj->closeCursor();

                echo "L'objet a été supprimé avec succès !";
            }

            if (!$donnees) {//Pas le bon mdp
                echo '<p class="error">'."Mot de passe de confirmation
                invalide !".'</p>';
            }

            $req->closeCursor();
        
        }
        
        if (!($_SERVER['REQUEST_METHOD'] == 'POST') || !$donnees) {

            $reqGetTitle = $bdd->prepare('SELECT Titre, ItemID
            FROM Objet
            WHERE Objet.ItemID = ?');
    
            $reqGetTitle->execute(array($_GET['ItemID']));
            $title = $reqGetTitle->fetch();
            $reqGetTitle->closeCursor();
    
            echo "<center><h3>" .  $title['Titre'] . "</h3></center>";
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST['mdp'])) {
                echo '<p class="error">'."Entrez un mot de passe !".'</p>';
            }

            
            echo '<form class="form" action="suppressObj.php?page=' . $_GET['page'] . '&ItemID=' .
            $_GET['ItemID'] . '" method="post">'

            ?>
            <p>
            Entrez votre mot de passe pour confirmer:<br>
            <input type='password' placeholder='Password' name='mdp' /> <br /><br/>
            
            <br />
            <input type='submit' value='Confirmer' />
            </p>
            </form>
                
            <?php
        }
        ?>   
    
        <br>
            <a href="liste_objets.php">
            <button class="button button1">Retour</button></a><br><br>     
    </body>
</html>