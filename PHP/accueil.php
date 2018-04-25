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

          <!-- <div id="image" ></div> -->

          <div class="section">
            <div class="produits">
              <h2>Les derniers ajouts</h2>
              <ul class = "cadre">
                <!-- <ul> -->
                <?php
                  $liste = $bdd->query('SELECT Titre, ItemID, PrixMin
                                        FROM Objet ORDER BY Objet.DateMiseEnVente 
                                        DESC LIMIT 0,10');

                  
                  while($donne = $liste->fetch()){

                    echo "<li class = \"item\"><a href=\"liste_objets.php?page=0"
                    . "&ItemID=" . $donne['ItemID'] . "\" >" . "<p class='rcorners corner2'>
                    ".$donne['Titre'] ."<br>" . "PRIX: " . "<mark class=\"price\">". 
                    $donne['PrixMin'] ." €" ." </mark>" ."</p>" . "</a></li>";

                  }
                  $liste->closeCursor();
                  ?>
              </ul>
            </div>
            

            <div class="produits">
              <h2>De nos meilleurs vendeurs</h2>
              <ul class="cadre">
                <?php
                  $liste = $bdd->query('SELECT AVG(Rate) AS eval_moyen,Seller,
                                        Titre, ItemID, PrixMin FROM Evaluation,Objet 
                                        WHERE Evaluation.Seller = Objet.SellerID 
                                        GROUP BY Seller ORDER BY eval_moyen DESC LIMIT 0,10');

                  while($donne = $liste->fetch()){

                    echo "<li class = \"item\"><a href=\"liste_objets.php?page=0"
                    . "&ItemID=" . $donne['ItemID'] . "\" >" . "<p class='rcorners corner2'>
                    ".$donne['Titre'] ."<br>" . "PRIX: " . "<mark class=\"price\">". 
                    $donne['PrixMin'] ." €" ." </mark>" ."</p>" . "</a></li>";

                  }
                  
                  $liste->closeCursor();
                ?>
            </ul>
            </div>
          </div>
        </div>
    </body>
</html>
