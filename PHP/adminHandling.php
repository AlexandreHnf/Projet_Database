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
            echo "<li><a href=# \" class = \"text_menu\">Supprimer un compte</a></li><br />";
            echo "<li><a href=# \" class = \"text_menu\">Modifier une catégorie</a></li><br />";
            echo "<li><a href=# \" class = \"text_menu\">Supprimer un article</a></li><br />";
            echo "<li><a href=# \" class = \"text_menu\">Consulter les historiques</a></li><br />";
            
            
        ?>



    </body>
</html>