<?php
  session_start();  // On démarre la session
  include('database.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css">
        <title>Accueil</title>
    </head>

    <body>
        <header>
          <?php include("menu.php"); ?>

        </header>

        <div class="section">
          <div class="produits">
            <?php echo "<br><br><br>" ?>
            <h2>Les derniers ajouts</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT Titre, ItemID
                                      FROM Objet ORDER BY Objet.DateMiseEnVente 
                                      DESC LIMIT 0,10');

                
                while($donne = $liste->fetch()){

                  echo "<li class = \"item\"><a href=\"liste_objets.php?page=1"
                  . "&ItemID=" . $donne['ItemID'] . "&a=1". "\" >" . "<p class='rcorners corner1'>
                  ".$donne['Titre'] ."</p>" . "</a></li>";

                }
                $liste->closeCursor();
                ?>
            </ul>
          </div>

          <div class="produits">
            <h2>Les plus vendus</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT COUNT(PropositionAchat.ItemID) AS nb_vente,
                                      PropositionAchat.ItemID, Titre
                                      FROM PropositionAchat, Objet 
                                      WHERE accepted = \'True\' 
                                      AND Objet.ItemID = PropositionAchat.ItemID 
                                      GROUP BY ItemID ORDER BY nb_vente DESC LIMIT 0,10' );

                while($donne = $liste->fetch()){

                  echo "<li class = \"item\"><a href=\"liste_objets.php?page=1"
                  . "&ItemID=" . $donne['ItemID'] . "&a=1". "\" >" . "<p class='rcorners corner1'>
                  ".$donne['Titre'] ."</p>" . "</a></li>";

                }
                $liste->closeCursor();
              ?>
            </ul>
          </div>

          <div class="produits">
            <h2>De nos meilleurs vendeurs</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT AVG(Rate) AS eval_moyen,Seller,
                                      Titre, ItemID FROM Evaluation,Objet 
                                      WHERE Evaluation.Seller = Objet.SellerID 
                                      GROUP BY Seller ORDER BY eval_moyen DESC LIMIT 0,10');

                while($donne = $liste->fetch()){

                  echo "<li class = \"item\"><a href=\"liste_objets.php?page=1"
                  . "&ItemID=" . $donne['ItemID'] . "&a=1". "\" >" . "<p class='rcorners corner1'>
                  ".$donne['Titre'] ."</p>" . "</a></li>";

                }
                
                $liste->closeCursor();
              ?>
           </ul>
          </div>
        </div>
    </body>
</html>
