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
        <title>Accueil</title>
    </head>

    <body>

        <div id="resize">
          <header>
            <?php include("menu.php"); ?>

          </header>

        <h2> Votre article a été ajouté avec Succès </h2>
        <br />
        <br> <a href="accueil.php">
        <button class="button button1">Retour</button></a>

    </body>
    

</html>