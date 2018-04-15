<?php
  session_start();
  include('database.php');
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo "recherche \"" . htmlspecialchars($_POST['recherche']) ."\""; ?></title>
  </head>
  <body>
    <header>
      <?php include('menu.php') ?>
    </header>
    <div class="ParentSearch">
      <div class="rechercheAvance">
        <form class="" action="recherche.php" method="post">
          <h4>Options de recherche avancées</h4>
          </br>
          <h5>Catégorie</h5>
          <select name="categorie">
            <?php
              $categories = $bdd->query('SELECT Titre FROM Categorie');
              while($donne = $categories->fetch()){
                echo "<option value= \"".$donne['Titre']. "\">" .$donne['Titre']. "</option>";
              }
              $categories->closeCursor();
             ?>
          </select>
          <h5>Ordre Alphabétique</h5>
          <select name="TriNom">
            <option value="croissant">croissant</option>
            <option value="decroissant">decroissant</option>
          </select>
          <h5>Prix</h5>
          <select name="Prix">
            <option value="croissant">croissant</option>
            <option value="decroissant">decroissant</option>
          </select>
          <h5>Date</h5>
          <select name="Date">
            <option value="croissant">croissante</option>
            <option value="decroissant">decroissante</option>
          </select>
          <h5>Vendeurs</h5>
          <p>Nom <input type="text" name = "prenom_vendeur"/></p>
          <p>Prenom <input type="text" name = "prenom_vendeur"/></p>
        </form>
      </div>
      <div class="resulatDeRecherche">
        <?php
          if(isset($_POST['Prix'])){echo"lol";}
          else{
            $recherche = $bdd->prepare('SELECT * FROM Objet WHERE ?');
          }
        ?>
      </div>
    </div>
  </body>
</html>
