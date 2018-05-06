<?php 
    session_start();  // On démarre la session
    include("database.php");
    include("function.php");
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <link rel="stylesheet" href="css/style.css">
       <title>Requetes</title>
   </head>

   <body>

        <header>
            <?php include("menu.php"); ?>
        </header>


        <?php

        if (isset($_GET['r']) && $_GET['r'] == 1) {
            echo "Les vendeurs qui sont appréciés par les utilisateurs ayant les même goûts que l'utilisateur 'Jules' <br>";
        }

        if (isset($_GET['r']) && $_GET['r'] == 2) {
            echo "<h2>Les vendeurs ayant vendu et acheté à la même personne</h2>";
        }

        if (isset($_GET['r']) && $_GET['r'] == 3) {
            echo "<h2>Les objets vendus à un prix inférieur au montant d'une proposition d'achat de cet objet</h2>";
        }

        if (isset($_GET['r']) && $_GET['r'] == 4) {
            echo "<h2>Le/les vendeurs ayant vendu le plus d'objets dans la même catégorie</h2>";
        }

        if (isset($_GET['r']) && $_GET['r'] == 5) {
            echo "<h2>Les objets pour lesquels au moins dix propositions d'achats ont été refusées</h2>";
        }

        if (isset($_GET['r']) && $_GET['r'] == 6) {
            echo "<h2>Les vendeurs avec le nombre moyen d'objets vendus, le nombre moyen d'objet achetés, la note qu'ils ont
            reçue, et la moyenne des notes qu'ils ont donné, et ce pour tout les vendeurs ayant vendu et acheté au moins
            10 objets</h2>";
        }

        ?>

   </body>
</html>
