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
        <title>Modifier une catégories</title>
    </head>

    <body>
        <header>
            <?php include("menu.php"); ?>
        </header>

        <h1> Modifier une catégorie  </h1>

        <?php
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (($_POST['categorie'] == "Default") && (!empty($_POST['titre']) && $_POST['titre'] != "Default")) {
                    echo '<p class="error">Vous ne pouvez pas modifier le nom de
                    la catégorie : "Default" </p>';

            }

            else {

                if (!empty($_POST['titre'])) {
                    $title = $_POST['titre'];
                }

                else {
                    $title = $_POST['categorie'];
                }

                if (!empty($_POST['desc'])) {
                    $descr = $_POST['desc'];
                }

                else {
                    $reqGetDesc = $bdd->prepare('SELECT Description_cat
                    FROM Categorie WHERE Titre = ?');
                    $reqGetDesc->execute(array($_POST['categorie']));
                    $descr = $reqGetDesc->fetch();
                    $reqGetDesc->closeCursor();
                }

                //Création de catégorie
                if (empty($_POST['titre'])) {
                    $reqModNoTitle = $bdd->prepare('UPDATE Categorie
                    SET Description_cat = ?, AdminID = ?
                    WHERE Titre = ?');
                    $reqModNoTitle->execute(array($descr, $_SESSION['id'], $_POST['categorie']));
                    $reqModNoTitle->closeCursor();   
                }
                
                else {
                    $reqModCat = $bdd->prepare('INSERT INTO Categorie(Titre, Description_cat, AdminID)
                    VALUES(:titre, :desc_cat, :adminid)');
                    $reqModCat->execute(array(    
                        'titre' => $title,
                        'desc_cat' => $descr,
                        'adminid' => $_SESSION['id']
                    ));
                    $reqModCat->closeCursor();
                

                    //Transfert des objets de l'ancienne catégorie vers celle-ci
                    $reqMod = $bdd->prepare('UPDATE Objet
                    SET Categorie = ?
                    WHERE Categorie = ?');
                    $reqMod->execute(array($title, $_POST['categorie']));
                    $reqMod->closeCursor();

                    //Suppression de la catégorie
                    $reqSup = $bdd->prepare('DELETE FROM Categorie
                    WHERE Titre = ?');
                    $reqSup->execute(array($_POST['categorie']));
                    $reqSup->closeCursor();
                }
                echo "Catégorie modifiée avec succès";
            }
            echo '<br /><a href="handleCategories.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>';

        }

        else {
        ?>
        
        <form class='form' action="modCat.php" method="post">
            Catégorie à modifier :
            <?php include("showCat.php"); ?>
            <br />

            <h3> Nouvelles entrées : </h3>

            Titre : <input type="text"  placeholder="Titre" name="titre"/> <br /> <br />
            Description : <br />
            <textarea placeholder="Description" name="desc" rows="8" cols="30">
            </textarea>
            <br /> <br /> 
            <input type="submit" value="Confirmer" />
        </form>
        
        <?php
        }
        ?>

    </body>
</html>