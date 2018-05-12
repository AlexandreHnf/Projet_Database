<?php
  session_start();  // On démarre la session
  include('database.php');
  include('function.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css">
        <title>Supprimer une catégories</title>
    </head>

    <body>
        <header>
            <?php include("menu.php"); ?>
        </header>

        <h1> Supprimer une catégorie  </h1>

        <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if ($_POST['categorie'] == "Default") {
                        echo '<p class="error">Vous ne pouvez pas supprimer la catégorie Default </p>';
                }

                else {
                    $mdp = $_POST['mdp'];
                    $hashed_mdp = SHA1($mdp);
 
                    if (empty($mdp)) {
                        echo '<p class="error">'."Vous n'avez pas entré votre
                         mot de passe !".'</p>';
                    }
        
                    else {
                        $reqCheckMdp = $bdd->prepare('SELECT Pseudo, MotDePasse, UserID FROM Utilisateur 
                                            WHERE Pseudo = ? AND MotDePasse = ?');
                        $reqCheckMdp->execute(array($_SESSION['pseudo'], $hashed_mdp)); // avec mdp hashé
                        $donnees = $reqCheckMdp->fetch();
            
                        
                        if (!$donnees) { // Si pas dans la db
                            echo '<p class="error">'."Mot de passe erroné !".'</p>';
                        }

                        else {
                            //Transfert des objets de cette catégorie vers la catégorie défaut
                            $reqMod = $bdd->prepare('UPDATE Objet
                            SET Categorie = ?
                            WHERE Categorie = ?');
                            $reqMod->execute(array("Default", $_POST['categorie']));
                            $reqMod->closeCursor();

                            //Suppression de la catégorie
                            $reqDelCat = $bdd->prepare('DELETE FROM Categorie
                            WHERE Titre = ?');
                            $reqDelCat->execute(array($_POST['categorie'])); // avec mdp hashé 
                            $reqDelCat->closeCursor();

                            echo "Catégorie supprimée avec succès";
                        }
                    }
                }

                echo '<br /><a href="handleCategories.php">
                <button class="button button1">Retour</button></a> ' . '<br><br>';
            }

            else {
                ?>
                
                <form class='form' action="suppressCat.php" method="post">
                    Catégorie à supprimer :
                    <?php include("showCat.php"); ?>
                    <br />
        
                    <h3> Confirmer les droits : </h3>
        
                    Mot de passe administrateur : <input type="password"  placeholder="Password" name="mdp"/> <br /> <br />
                    <input type="submit" value="Confirmer" />
                </form>

            <?php
            }
            ?>    

    </body>
</html>