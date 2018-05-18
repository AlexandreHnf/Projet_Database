<?php
    session_start();  // On démarre la session
    include('database.php');
    include("function.php");
?>

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <link rel="stylesheet" href="css/style.css">
       <!--script language="javascript" type="text/javascript" src="galerie.js">
       </script-->
       <script language="javascript">
        var id = 0;
         function next(images,itemID){
           id = (id+1)%images.length;
           document.getElementById("source").src = "png/"+ itemID+"/" +images[id];
         }
         function previous(images,itemID){
           if(id== 0) id= images.length;
           --id;
           document.getElementById("source").src = "png/"+ itemID+"/" +images[id];
         }
         function redirectOnSelection(){
           var currentUrl = "" + window.location;
           var stripedUrl = currentUrl.split("&tri");
           var stripedUrlQ = currentUrl.split(".php");
           stripedUrl = stripedUrl[0];
           if(stripedUrlQ[1].charAt(0) != "?"){stripedUrl += "?";}
           var newLocation  = stripedUrl + "&tri="+document.getElementById("triPar").value+"&ordre="+document.getElementById('croissance').value;
           window.location = newLocation;
         }
       </script>
       <title>Objets</title>
   </head>

   <body>
        <header>
            <?php include("menu.php"); ?>
        </header>

        <?php

        if (isset($_GET['page']) and isset($_GET['ItemID'])) {
            // on affiche l'objet

            $req2 = $bdd->prepare('SELECT Titre, Description_obj, DateMiseEnVente,
                                    PrixMin, DateVente, Acheteur, Categorie,
                                    Pseudo, Vendeur.SellerID
                                    FROM Objet, Utilisateur, Vendeur
                                    WHERE Objet.ItemID = ?
                                    AND Objet.SellerID = Vendeur.SellerID
                                    AND Utilisateur.UserID = Vendeur.SellerID ');

            $req2->execute(array($_GET['ItemID']));
            $objet = $req2->fetch();
            $req2->closeCursor();

            // affichage du lien pour retour
            if ($_GET['page'] == 0) { // Si on vient d'un objet de l'accueil
                echo '<a href="accueil.php">
                <button class="button button1">Retour</button></a> ' . '<br><br>';
            }
            else {
                echo '<a href="liste_objets.php?page=' . $_GET['page'] . '">
                <button class="button button1">Retour</button></a> ' . '<br><br>';
            }


            // ========================== CARACTERISTIQUES OBJET =========================

            echo "<h2> Caractéristiques de l'objet </h2>";

            echo "<table>"; // Tableau

            // Lignes dans le tableau
            echo "<tr>" . "<th>Titre</th>" . "<td>" . $objet['Titre'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Description</th>" . "<td>" . $objet['Description_obj'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Date de mise en vente</th>" . "<td>" . $objet['DateMiseEnVente'] .
            "</td>" . "</tr>";
            echo "<tr>" . "<th>Prix minimum</th>" . "<td>" . $objet['PrixMin'] ." €"."</td>" . "</tr>";
            echo "<tr>" . "<th>Date de vente</th>" . "<td>" . $objet['DateVente'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Acheteur</th>" . "<td>" . $objet['Acheteur'] . "</td>" . "</tr>";

            echo "<tr>"."<th>Vendeur</th>" . "<td>". "<a href=\"profil_vendeurs.php?page=0&SellerID=" .
                $objet['SellerID'] . "\" >
                " . $objet['Pseudo'] . "</a>" . "</td>" . "</tr>";

            echo "<tr>" . "<th>Catégorie</th>" . "<td>" . $objet['Categorie'] . "</td>" . "</tr>";

            echo "</table>" . "<br><br>";
            ?>
            <!--// ========================== GALERIE OBJET =========================-->
            <div class="Galerie" align='center'>
              <h1>Galerie</h1>
              <?php
              $dossier = "png/". $_GET['ItemID'];
              if(is_dir($dossier)){
                $img = scandir($dossier);
                $jsImg = array();
                $ko = array("",".","..");
                for($i = 0;$i<count($img);++$i){
                  if(!in_array($img[$i],$ko)){
                    $jsImg[] =$img[$i];
                  }
                }
                $var = json_encode($jsImg);
                $firstImg = "png/{$_GET['ItemID']}/{$jsImg[0]}";
                ?>
                <img src="png/left.png" alt="LEFT" width="52" height="52" onclick='previous(<?php echo $var ?>,<?php echo $_GET["ItemID"] ?>)'>
                <img src="<?php echo $firstImg; ?>" alt="IMAGES" width="975" height="570" id = "source">
                <img src="png/right.png" alt="RIGHT" width="52" height="52" onclick='next(<?php echo $var ?>,<?php echo $_GET["ItemID"] ?>)'>
              <?php }
              else{echo "Pas d'image dans la galerie";}
              ?>
            </div>
          </br>
            <?php
            if (!empty($_SESSION['pseudo'])){
                //Suppression si administrateur ou le vendeur de l'objet
                if (isAdmin($_SESSION['pseudo'])  || isItemSeller($_SESSION['pseudo'],$_GET["ItemID"])) {
                    echo "<div class='cadre'>";
                    echo "<a href=\"suppressObj.php?page=" .
                        $_GET['page'] . "&ItemID=" . $_GET['ItemID'] . "\" >"
                        . "<button class='button button1'>
                        " . "Supprimer cet objet" . "</button>" . "</a>";
                    echo "</div>";
                }
            }

            //LOCALISATION
            echo "<div class='cadre'>";
            echo "<center><h3>Localisation</h3></center>";

            $reqAdr = $bdd->prepare('SELECT Adresse FROM Vendeur
            WHERE SellerID = ?');
            $reqAdr->execute(array($objet['SellerID']));
            $donnees = $reqAdr->fetch();
            $reqAdr->closeCursor();

            $adr = $donnees['Adresse'];

            $adr = preg_replace('/\s/', '+', $adr);
            $source = "https://www.google.com/maps/embed/v1/place?key=AIzaSyBV490JeqrQVBHJFEqw8pCZdMawjP9gxjA&amp;q=" . $adr . ",Belgium";
            ?>

            <iframe
            width="500"
            height="450"
            frameborder="0" style="border:0"
            src="<?php echo $source ?>" allowfullscreen>
            </iframe>

            <?php

            echo "</div>";


            // LIEN VERS PROPOSITION D'ACHAT

            $req4 = $bdd->prepare('SELECT accepted
                        FROM PropositionAchat
                        WHERE PropositionAchat.ItemID = ?
                        AND accepted = "True"');
            $req4->execute(array($_GET['ItemID']));
            $prop2 = $req4->fetch();
            $req4->closeCursor();

            // Si il n'a pas encore fait de proposition pour cet objet

            if (!$prop2) {
                echo "<div class='cadre'>";
                echo "<a href=\"proposition_achat.php?page=" .
                    $_GET['page'] . "&ItemID=" . $_GET['ItemID'] . "\" >" . "<button class='button button1'>"
                    . "<mark class=\"price\">" . "Faire une proposition d'achat pour cet objet"
                    ." </mark>" . "</button>" . "</a></li>";
                echo "</div>";
            }


            // ==================== PROPOSITION ACHAT ==============================

            echo "<br>" . "<h2> Les propositions d'achat </h2>";

            $req3 = $bdd->prepare('SELECT Pseudo, Time, price, accepted
                                    FROM Utilisateur, PropositionAchat
                                    WHERE PropositionAchat.ItemID = ?
                                    AND PropositionAchat.Buyer = Utilisateur.UserID');
            $req3->execute(array($_GET['ItemID']));
            $prop = $req3->fetch();
            $req3->closeCursor();
            echo "<table>"; // Tableau

            echo "<tr>" . "<th>Pseudo de l'acheteur potentiel </th>" . "<th>Date</th>";
            echo "<th>Prix proposé</th>" . "<th>Statut</th>" . "</tr>";

            echo "<tr>"."<td>" . $prop['Pseudo'] . "</td>".
                "<td>" . $prop['Time'] . "</td>" .
                "<td>" . $prop['price'] . "</td>";

            if (isset($prop['accepted'])) {
                if ($prop['accepted'] == 'True') {
                    echo "<td>accepté </td>" . "</tr>";
                }
                else {
                    echo "<td>refusé</td>" . "</tr>";
                }
            }

        }

        else{ // On affiche la liste des vendeurs
            $select= 'SELECT *';
            $from = ' FROM Objet ';
            $orderBy = '';
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            else {
                $page = 1; // par défaut
            }

            if (($_SERVER['REQUEST_METHOD'] == 'GET') && (isset($_GET['quantity']))) {
              if((int)$_GET['quantity'] <20){$_GET['quantity']= 20;}
              elseif((int)$_GET['quantity']>100){$_GET['quantity']= 100;}
              $nb_mess_per_page = $_GET['quantity']; // Nombre d'éléments par page
            }

            else {
                $nb_mess_per_page = 100; // Nombre d'éléments par page
            }

            if(isset($_GET['tri'])){
              $arrayTri = array("PrixMin","","DateMiseEnVente","Titre","n.Moyenne");
              if(!in_array($_GET['tri'],$arrayTri)){$_GET['tri'] = "";}
              //tri
              if($_GET['tri'] != ""){
                if(isset($_GET['ordre']) && ($_GET['ordre'] == "ASC" || $_GET['ordre'] == "DESC")){$nothing = "";}
                else{$_GET['ordre'] = "ASC";}
                $orderBy = ' ORDER BY ' . $_GET['tri'] ." ". $_GET['ordre'];

                if($_GET['tri'] == "n.Moyenne"){
                  $from = $from ."LEFT JOIN (SELECT AVG(Rate) AS Moyenne,Seller from Evaluation GROUP BY Seller) n ON n.Seller = Objet.SellerID ";
                }
              }
            }

            // On récupère le nombre total de messages
            $req = $bdd->query('SELECT COUNT(*) AS nb_messages FROM Objet');
            $donnees = $req->fetch();
            $total = $donnees ['nb_messages'];

            // On calcule le nombre de pages à créer
            $nombreDePages  = ceil($total / $nb_mess_per_page);
            $next_page = ($page + 1) % $nombreDePages;
            $prev_page = ($page - 1) % $nombreDePages;
            if ($prev_page == 0) {$prev_page = $nombreDePages;}


            echo "<br><br>";
            echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>';

            // Pour choisir combien d'éléments à afficher par page

            echo "<form class='form' action='liste_objets.php' method='get'>";
            echo "<p>";
                echo "Nombre d'élements par page" . "<br>";
                echo "<input type='number' name='quantity' min='20' max='100' value='{$nb_mess_per_page}'>" . "<br>";
				echo "<input type='submit' value='Valider' />";
			echo "</p>";

		    echo "</form>";

            // On calcule le numéro du premier message qu'on prend pour le LIMIT
            $premierMessageAafficher = ($page - 1) * $nb_mess_per_page;
            $fullRequest = $select . $from . $orderBy . ' LIMIT ' . $premierMessageAafficher . ', ' . $nb_mess_per_page;
            $req1 = $bdd->query($fullRequest);

            echo "<h1> Liste des objets mis en vente </h1>";
            ?>
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
            <?php
            echo "<ul class='cadre'>";
            while ($obj = $req1->fetch()) {
                $id = $obj['ItemID'];
                // print des liens

                echo "<li class = \"item\"><a href=\"liste_objets.php?page=" .
                $page . "&ItemID=" . $id . "\" >" . "<p class='rcorners corner2'>
                ".$obj['Titre']. "<br>" . "PRIX: " . "<mark class=\"price\">".
                $obj['PrixMin'] ." €" ." </mark>" ."</p>" . "</a></li>";
            }
            $req1->closeCursor();

            echo "<ul />";

            echo "<br><br>";

            echo "<div class='cadre'>";

            // Puis on fait une boucle pour écrire les liens vers chacune des pages

            echo '<a class=\'page\' href="liste_objets.php?page='.$prev_page.'">'.'<<'.'</a>';
            $startP = $page < 7 ? 1:($page-5);
            $startP = $page < ($nombreDePages -9) ? $startP:($nombreDePages -9);
            $startP =  $startP < 1 ? 1:$startP;
            if($nombreDePages <=10){
              $endP = $nombreDePages;
            }
            elseif($startP >= ($nombreDePages -9)){
              $endP = $nombreDePages;
            }
            else{$endP = $startP+9;}
            for ($i = $startP ; $i <= $endP ; $i++) {
                if ($i == $page) {
                    echo '<a class=\'active\' href="liste_objets.php?page='.$i.'">'.$i .'</a>';
                }
                else {
                    echo '<a class=\'page\' href="liste_objets.php?page='.$i.'">'.$i . '</a>';
                }
            }
            echo '<a class=\'page\' href="liste_objets.php?page='.$next_page.'">'.'>>'.'</a>';

            echo "<div />";
        }

        ?>

   </body>
</html>
