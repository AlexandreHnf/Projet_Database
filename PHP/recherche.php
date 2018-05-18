<?php
  session_start();
  include('database.php');
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo "recherche \"" . htmlspecialchars($_GET['recherche']) ."\""; ?></title>
    <script language="javascript">
      function redirectOnSelection(){
        var currentUrl = "" + window.location;
        var stripedUrl = currentUrl.split("&tri");
        stripedUrl = stripedUrl[0];
        var newLocation  = stripedUrl +"&tri="+document.getElementById("triPar").value+"&ordre="+document.getElementById('croissance').value;
        window.location = newLocation;
      }
    </script>
  </head>
  <body>
    <header>
      <?php include('menu.php') ?>
    </header>
    <div class="ParentSearch">
      <?php
        /*gestion prix min max */
        if(isset($_GET['MiniPrix']) && isset($_GET['MaxPrix'])){
          $pxFlag = $_GET['MiniPrix'] == "" && $_GET['MaxPrix'] == "" ? false:true;
          if($pxFlag){
            $px = array((int)$_GET['MiniPrix'],(int)$_GET['MaxPrix']);
            sort($px);
            $_GET['MiniPrix'] = $px[0];
            $_GET['MaxPrix'] = $px[1];
            $_GET['MiniPrix'] = $_GET['MiniPrix'] == "" ? 0:$_GET['MiniPrix'];
            $_GET['MaxPrix'] = $_GET['MaxPrix'] == "" ? 0:$_GET['MaxPrix'];
          }
        }
      ?>
      <div class="rechercheAvance">
        <form class="" action="recherche.php">
          <h4>Options de recherche avancées</h4>
          <?php
            echo "<input type=\"text\" placeholder=\"recherche...\" value=\""
            .htmlspecialchars($_GET['recherche']).
            "\" id=\"subdomain\" name = \"recherche\" />";
          ?>
          </br>
          <h5>Catégorie</h5>
          <select name="categorie">
            <?php
              $categories = $bdd->query('SELECT Titre FROM Categorie');
              $arrayCategorie = array();
              while($donne = $categories->fetch()){
                echo "<option value= \"".$donne['Titre']. "\">" .$donne['Titre']. "</option>";
                $arrayCategorie[] = $donne['Titre'];
              }
              $categories->closeCursor();
             ?>
          </select>

          <div class="vendeur">
            <h5>Vendeurs</h5>
            <input type="text" name="nom" placeholder="Nom" <?php
            if (isset($_GET['nom'])){ echo "value=\"{$_GET['nom']}\"";}
            ?>>
            </br>
            </br>
            <input type="text" name="prenom" placeholder="Prenom" <?php
            if (isset($_GET['prenom'])){ echo "value=\"{$_GET['prenom']}\"";}
            ?>>
          </div>
          <div class="tranchePrix">
            <h5>Prix</h5>
            <p>
              <input type="text" name="MiniPrix" placeholder="Min" <?php
              if (isset($_GET['MiniPrix'])){ echo "value=\"{$_GET['MiniPrix']}\"";}
              ?>>
              </br>
              </br>
              <input type="text" name="MaxPrix" placeholder="Max" <?php
              if (isset($_GET['MaxPrix'])){ echo "value=\"{$_GET['MaxPrix']}\"";}
              ?>>
            </p>
          </div>
          <div class="note">
            <h5>Note vendeur minimale</h5>
            <select name="min">
              <option value="0" <?php if(isset($_GET['min']) &&  $_GET['min'] == "0"){echo "selected";} ?>>0</option>
              <option value="1" <?php if(isset($_GET['min']) &&  $_GET['min'] == "1"){echo "selected";} ?>>1</option>
              <option value="2" <?php if(isset($_GET['min']) &&  $_GET['min'] == "2"){echo "selected";} ?>>2</option>
              <option value="3" <?php if(isset($_GET['min']) &&  $_GET['min'] == "3"){echo "selected";} ?>>3</option>
              <option value="4" <?php if(isset($_GET['min']) &&  $_GET['min'] == "4"){echo "selected";} ?>>4</option>
              <option value="5" <?php if(isset($_GET['min']) &&  $_GET['min'] == "5"){echo "selected";} ?>>5</option>
            </select>
          </div>
          </br>
          <input type="submit" value="🔎" />
        </form>
      </div>
      <div class="resulatDeRecherche">
        <div class="tri">
          <h5>Trié par</h5>
          <select name="tri" id ="triPar" onChange="redirectOnSelection()">
            <option value="" <?php if(isset($_GET['tri']) &&  $_GET['tri'] == ""){echo "selected";} ?>></option>
            <option value="PrixMin" <?php if(isset($_GET['tri']) &&  $_GET['tri'] == "PrixMin"){echo "selected";} ?>>Prix</option>
            <option value="DateMiseEnVente" <?php if(isset($_GET['tri']) &&  $_GET['tri'] == "DateMiseEnVente"){echo "selected";} ?>>Date</option>
            <option value="Titre" <?php if(isset($_GET['tri']) &&  $_GET['tri'] == "Titre"){echo "selected";} ?>>Nom</option>
            <option value="n.Moyenne" <?php if(isset($_GET['tri']) &&  $_GET['tri'] == "n.Moyenne"){echo "selected";} ?>>Note vendeurs</option>
          </select>
          <select name="ordre" id = "croissance" onChange="redirectOnSelection()">
            <option value="ASC" <?php if(isset($_GET['ordre']) &&  $_GET['ordre'] == "ASC"){echo "selected";} ?>>Croissant</option>
            <option value="DESC" <?php if(isset($_GET['ordre']) &&  $_GET['ordre'] == "DESC"){echo "selected";} ?>>Decroisant</option>
          </select>
        </div>
        <div class="displayItem">
          <?php
            /* Verification de certaines variables */
              //si la var get a été effacé redirection vers une recherche vide
              if(!isset($_GET['recherche'])){header('Location: recherche.php?recherche=');}
              //si tentative de modif de la categorie dans l'url
              if(!isset($_GET['categorie'])){$_GET['categorie'] = "Default";}
              if(!in_array($_GET['categorie'],$arrayCategorie)){header('Location: recherche.php?recherche=&categorie=Default');}
              if(isset($_GET['min'])){
                $_GET['min'] = (int) $_GET['min'];
                if($_GET['min'] > 5){$_GET['min'] = 5;}
                elseif($_GET['min'] < 0){$_GET['min'] = 0;}
              }
              if(!isset($_GET['pg'])){$_GET['pg'] = 1;}
              //split des mots clef dans un array et creation de la requete
              $_GET['recherche'] = htmlspecialchars($_GET['recherche']);
              $splitSearch = explode(" ",$_GET['recherche']);
              //Mot clef exclus pour empecher une recherche trop disperser
              $exclusion = array("le","la","un","une","pour","les","des","à","avec","*","avec","et","ou","de","-");
              $req = '';
              $orLim = true; //Juste pour le where et premier or
              $keyWords = array();
              foreach ($splitSearch as $key) {
                //seulement les mots hors exclusion ou si un seul mot alors les exclus sont autorisés
                if(!in_array(mb_strtolower($key),$exclusion) || count($splitSearch) == 1){
                  $keyWords[] = "%{$key}%";
                  if($orLim) {
                    $orLim = false;
                    $req = $req . "WHERE ";
                  }
                  else{$req = $req . "OR ";}
                  $req = $req . " Titre LIKE ? ";
                }
              }
              echo "<ul>";
              //split de la requete en variables
              $select= 'SELECT *';
              $from = ' FROM Objet ';
              $orderBy = '';
              $groupBy = '';
              $having = '';
              $limit = ' LIMIT ';
              $req = $req . " AND Categorie = '{$_GET['categorie']}' ";

              if(isset($_GET['tri']) || isset($_GET['min'])){
                $arrayTri = array("PrixMin","","DateMiseEnVente","Titre","n.Moyenne");
                if(isset($_GET['tri']) && !in_array($_GET['tri'],$arrayTri)){$_GET['tri'] = "";}
                //tri
                if(isset($_GET['tri']) && $_GET['tri'] != ""){
                  if(isset($_GET['ordre']) && ($_GET['ordre'] == "ASC" || $_GET['ordre'] == "DESC")){$nothing = "";}
                  else{$_GET['ordre'] = "ASC";}
                  $orderBy = ' ORDER BY ' . $_GET['tri'] ." ". $_GET['ordre'];

                  if($_GET['tri'] == "n.Moyenne"){
                    $from = $from ."LEFT JOIN (SELECT AVG(Rate) AS Moyenne,Seller from Evaluation GROUP BY Seller) n ON n.Seller = Objet.SellerID ";
                  }
                }
                //Recherche avancé
                if(isset($_GET['min']) && isset($_GET['prenom']) && isset($_GET['nom']) ){
                  if(!($_GET['prenom'] == "" && $_GET['nom'] == "")){
                    $select = $select . ',Nom,Prenom ';
                    $from = $from . ',Vendeur ';
                    $req = $req . ' AND Objet.SellerID = Vendeur.SellerID AND Nom LIKE ? AND Prenom LIKE ? ';
                    $keyWords[] = "%{$_GET['nom']}%";
                    $keyWords[] = "%{$_GET['prenom']}%";
                  }
                  //si prix fix
                  if($pxFlag){
                    $req = $req . " AND Objet.PrixMin >= {$_GET['MiniPrix']} AND Objet.PrixMin <= {$_GET['MaxPrix']} ";
                  }
                  // si pas de tri via note
                  if (!(isset($_GET['tri']) && $_GET['tri'] == "n.Moyenne")) {
                    $from = $from ."LEFT JOIN (SELECT AVG(Rate) AS Moyenne,Seller from Evaluation GROUP BY Seller) n ON n.Seller = Objet.SellerID ";
                  }
                  $req = $req ." AND n.Moyenne >= {$_GET['min']} ";
                }
                /*Query*/
                //requete complete
              }
                //Comptage pour pagination
                $pStart = 20 * ((int)$_GET['pg']-1);
                $pEnd =  20 * ((int)$_GET['pg']);
                $limit = $limit . $pStart ."," . $pEnd;
                $req =  $select . $from . $req . $groupBy . $having .$orderBy;
                //si pas deja fait compter le nombre de requete et placer dans session
                $nbRef = explode("&pg=",$_SERVER['REQUEST_URI']);
                $nbQuery = "SELECT COUNT(*) FROM " . "( " . $req .") nbquery";
                $totalQuery = $bdd->prepare($nbQuery);
                $totalQuery->execute($keyWords);
                $nbQuery = $totalQuery->fetch();
                $nbQuery = $nbQuery[0];
                $totalQuery->closeCursor();
                $nbPage = ceil($nbQuery/20);

                if((int)$_GET['pg'] == 0 || (int)$_GET['pg'] > $nbPage){
                  http_response_code(404);
                }
                // query affichage item
                $req = $req . $limit;
                $recherche = $bdd->prepare($req);
                $recherche->execute($keyWords);

                while ($result = $recherche->fetch()){
                  echo "<li class = \"item\"><a href=\"liste_objets.php?page=1"
                  . "&ItemID=" . $result['ItemID'] . "&a=1". "\" >" . "<p class='rcorners corner2'>
                  ".$result['Titre'] ."<br>" . "PRIX: " . "<mark class=\"price\">".
                  $result['PrixMin'] ." €" ." </mark>" ."</p>" . "</a></li>";
                }
                $recherche->closeCursor();
              echo "</ul>";
            ?>
        </div>
        <div class="pagination">
          <ul>
            <?php
            // Calcule des puces et generation de liens
              $param = explode("recherche.php",$nbRef[0]);
                $startP = $_GET['pg'] < 7 ? 1:($_GET['pg']-5);
                $startP = $_GET['pg'] < ($nbPage -9) ? $startP:($nbPage -9);
                $startP =  $startP < 1 ? 1:$startP;
                if($nbPage <=10){
                  $endP = $nbPage;
                }
                elseif($startP >= ($nbPage -9)){
                  $endP = $nbPage;
                }
                else{$endP = $startP+9;}
              for($i = $startP; $i <= $endP; $i++){
                if($i != $_GET['pg']){
                  echo "<li><a class='page' href='recherche.php{$param[1]}&pg={$i}'> {$i} </a></li>";
                }
                else{
                  echo "<li> {$i} </li>";
                }
              }
            ?>
          </ul>
        </div>
      </div>
    </div>
  </body>
</html>
