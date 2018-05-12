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
        <title>Gestion des Catégories</title>
    </head>

    <body>

        <div id="resize">
          <header>
            <?php include("menu.php"); ?>
          </header>

        <h1> Gestions des Catégories</h1>

        <table>

        <th><a href="addCat.php">
        <button class = "button button1"> Ajouter une catégorie </button>
        </a>
        </th>

        <th>
        <a href="modCat.php">
        <button class = "button button1"> Modifier une catégorie </button>
        </a>
        </th>

        <th>
        <a href="suppressCat.php">
        <button class = "button button1"> Supprimer une catégorie </button>
        </a>
        </th>

        </br>
        </table>

        <center>
        <h3> Liste des catégories </h3>
        </center>
        <?php
            $reqCat = $bdd->prepare('SELECT Titre, Description_cat FROM Categorie');
            $reqCat->execute();

            echo "<table>";
            echo "<tr> <th> Titre </th> <th>Description</th> </tr>";
            while ($Cat = $reqCat->fetch()) {
                    $titre = $Cat['Titre'];
                    $desc = $Cat['Description_cat'];
                    // print des liens

                    echo "<tr><td>" . $titre . "</td> <td>" . $desc . "</td> </tr>";
            }
            $reqCat->closeCursor();
            echo "</table>";

        // affiche du lien pour retour
        echo '<br>' . '<a href="accueil.php">
        <button class="button button1">Retour</button></a> ';
        ?>
    </body>
</html>