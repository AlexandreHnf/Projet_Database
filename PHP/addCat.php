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
        <title>Ajouter une catégories</title>
    </head>

    <body>
        <header>
            <?php include("menu.php"); ?>
        </header>

        <h1> Nouvelle catégorie  </h1>

        <?php
        $error = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isCat($_POST['titre'])) {
                $reqaddCat = $bdd->prepare('INSERT INTO Categorie(Titre, Description_cat, AdminID)
                VALUES(:titre, :desc_cat, :adminid)');
                $reqaddCat->execute(array(    
                    'titre' => $_POST['titre'],
                    'desc_cat' => $_POST['desc'],
                    'adminid' => $_SESSION['id']
                ));
                $reqaddCat->closeCursor();

                echo "Catégorie ajoutée avec succès";
            }

            else {
                $error = true;
            }
        }

        if ($error or !($_SERVER['REQUEST_METHOD'] == 'POST')) {
            if ($error) {
                echo '<p class="error">' . "Erreur : la catégorie existe déjà !" . '</p>';
            }
            ?>

            <form class='form' action="addCat.php" method="post">
                Titre : <input type="text"  placeholder="Titre" name="titre"/> <br /> <br />
                Description : <input type="text"  placeholder="Description" name="desc"/> <br /> <br />
                <input type="submit" value="Ajouter" />
            </form>

        <?php
        }
        ?>

    </body>
</html>