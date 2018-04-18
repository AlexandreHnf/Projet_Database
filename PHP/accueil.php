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
          <?php include("researchTool.php"); ?>
        </header>
        <div class="section">
          <div class="produits">
            <h2>Les derniers ajouts</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT Titre FROM Objet ORDER BY Objet.DateMiseEnVente DESC LIMIT 0,10');/*'SELECT COUNT(ItemID) AS nb_vente,ItemID FROM PropositionAchat WHERE accepted = \'True\' GROUP BY ItemID ORDER BY nb_vente DESC LIMIT 0,20')*/;
                while($donne = $liste->fetch()){
                  // echo "<li class = \"item\"><a href=# >" . $donne['Titre'] . "</a></li>";
                  echo "<li class = \"item\"><a href=# >" . "<p id='rcorners1'>".$donne['Titre']."</p>" . "</a></li>";
                  
                }
                $liste->closeCursor();
                ?>
            </ul>
          </div>

          <div class="produits">
            <h2>Les plus vendus</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT COUNT(PropositionAchat.ItemID) AS nb_vente,PropositionAchat.ItemID,Titre FROM PropositionAchat,Objet WHERE accepted = \'True\' AND Objet.ItemID = PropositionAchat.ItemID GROUP BY ItemID ORDER BY nb_vente DESC LIMIT 0,10' );
                while($donne = $liste->fetch()){
                  // echo "<li class = \"item\"><a href=#>" . $donne['Titre'] . "</a></li>";
                  echo "<li class = \"item\"><a href=# >" . "<p id='rcorners1'>".$donne['Titre']."</p>" . "</a></li>";
                }
                $liste->closeCursor();
              ?>
            </ul>
          </div>

          <div class="produits">
            <h2>De nos meilleurs vendeurs</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT AVG(Rate) AS eval_moyen,Seller,Titre FROM Evaluation,Objet WHERE Evaluation.Seller = Objet.SellerID GROUP BY Seller ORDER BY eval_moyen DESC LIMIT 0,10');
                while($donne = $liste->fetch()){
                  // echo "<li class = \"item\"><a href=# >" . $donne['Titre'] . "</a></li>";
                  echo "<li class = \"item\"><a href=# >" . "<p id='rcorners1'>".$donne['Titre']."</p>" . "</a></li>";
                }
                $liste->closeCursor();
              ?>
           </ul>
          </div>
        </div>
    </body>
</html>
