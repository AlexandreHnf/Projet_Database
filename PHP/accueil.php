<?php
  session_start();  // On démarre la session
  include('database.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../css/style.css">
        <title>Accueil</title>
    </head>

    <body>
        <header>
          <?php include("menu.php"); ?>
        </header>
        <div class="section">
          <div class="produits">
            <h2>Les derniers ajouts</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT Titre FROM objet ORDER BY objet.DateMiseEnVente DESC LIMIT 0,10');/*'SELECT COUNT(ItemID) AS nb_vente,ItemID FROM propositionachat WHERE accepted = \'True\' GROUP BY ItemID ORDER BY nb_vente DESC LIMIT 0,20')*/;
                while($donne = $liste->fetch()){
                  echo "<li><a href=# class = \"item\">" . $donne['Titre'] . "</a></li>";
                }
                $liste->closeCursor();
                ?>
            </ul>
          </div>

          <div class="produits">
            <h2>Les plus vendu</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT COUNT(propositionachat.ItemID) AS nb_vente,propositionachat.ItemID,Titre FROM propositionachat,objet WHERE accepted = \'True\' AND objet.ItemID = propositionachat.ItemID GROUP BY ItemID ORDER BY nb_vente DESC LIMIT 0,10' );
                while($donne = $liste->fetch()){
                  echo "<li><a href=# class = \"item\">" . $donne['Titre'] . "</a></li>";
                }
                $liste->closeCursor();
              ?>
            </ul>
          </div>

          <div class="produits">
            <h2>De nos meilleurs vendeurs</h2>
            <ul>
              <?php
                $liste = $bdd->query('SELECT AVG(Rate) AS eval_moyen,Seller,Titre FROM evaluation,objet WHERE evaluation.Seller = objet.SellerID GROUP BY Seller ORDER BY eval_moyen DESC LIMIT 0,10');
                while($donne = $liste->fetch()){
                  echo "<li><a href=# class = \"item\">" . $donne['Titre'] . "</a></li>";
                }
                $liste->closeCursor();
              ?>
           </ul>
          </div>
        </div>
    </body>
</html>


<?php

function isSeller($pseudo)
{
    include("database.php");
    $test = $bdd->prepare(' SELECT v.SellerID 
                            FROM Vendeur v, Utilisateur u 
                            WHERE v.SellerID = u.UserID AND u.Pseudo = ?'
                        );

    $test->execute(array($_SESSION['pseudo']));
    $res = $test->fetch();
    
    return isset($res['SellerID']);
}

?>