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

            <h1> Droits Administrateurs </h1>

            <div class="cadre">

            <a href="addAdmin.php">
            <button class = "button button1"> Ajouter un administrateur</button>
            </a>
            <br/>

            <a href="suppressAccount.php">
            <button class = "button button1"> Supprimer un compte</button>
            </a>
            <br/>

            <a href="handleCategories.php">
            <button class = "button button1"> Gestion des catégories</button>
            </a>

            </div>

    </body>
</html>