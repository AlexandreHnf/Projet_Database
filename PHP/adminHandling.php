<?php
  session_start();  // On démarre la session
  include('database.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css">
        <title>Droits administrateur</title>
    </head>

    <body>
        <header>
          <?php include("menu.php"); ?>
        </header>

        <?php
            echo "<li><a href=addAdmin.php \" class = \"text_menu\">Ajouter un administrateur</a></li><br />";
            echo "<li><a href=suppressAccount.php \" class = \"text_menu\">Supprimer un compte</a></li><br />";
            echo "<li><a href=handleCategories.php \" class = \"text_menu\">Gestion des catégories</a></li><br />";
            echo "<li><a href=# \" class = \"text_menu\">Gestion des articles</a></li><br />";
        ?>
    </body>
</html>