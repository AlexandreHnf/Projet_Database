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
                                    PrixMin, DateVente, Acheteur, Categorie, Pseudo 
                                    FROM Objet, Utilisateur, Vendeur 
                                    WHERE Objet.ItemID = ? 
                                    AND Objet.SellerID = Vendeur.SellerID 
                                    AND Utilisateur.UserID = Vendeur.SellerID ');

            $req2->execute(array($_GET['ItemID']));
            $objet = $req2->fetch();
            $req2->closeCursor();

            // affiche du lien pour retour
            if (isset($_GET['a'])) { // Si on vient d'un objet de l'accueil
                echo '<a href="accueil.php">
                <button class="button button1">Retour</button></a> ' . '<br><br>';
            }
            else {
                echo '<a href="liste_objets.php?page=' . $_GET['page'] . '">
                <button class="button button1">Retour</button></a> ' . '<br><br>';
            }

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
            echo "<tr>" . "<th>Vendeur</th>" . "<td>" . $objet['Pseudo'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Catégorie</th>" . "<td>" . $objet['Categorie'] . "</td>" . "</tr>";

            echo "</table>";

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
                if ($prop['accepted'] == True) {
                    echo "<td>accepté </td>" . "</tr>";
                }
                else {
                    echo "<td>refusé</td>" . "</tr>";
                }
            }

        }

        else{ // On affiche la liste des vendeurs

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            else { 
                $page = 1; // par défaut
            }

            if (($_SERVER['REQUEST_METHOD'] == 'POST') && (isset($_POST['quantity']))) {
                $nb_mess_per_page = $_POST['quantity']; // Nombre d'éléments par page
            }
            
            else {
                $nb_mess_per_page = 100; // Nombre d'éléments par page
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

            echo "<form class='form' action='liste_objets.php' method='post'>";
            echo "<p>";
                echo "Nombre d'élements par page" . "<br>";
                echo "<input type='number' name='quantity' min='20' max='100'>";
				echo "<input type='submit' value='Valider' />";
			echo "</p>";

		    echo "</form>";

            // On calcule le numéro du premier message qu'on prend pour le LIMIT
            $premierMessageAafficher = ($page - 1) * $nb_mess_per_page;

            $req1 = $bdd->query('SELECT ItemID, Titre, PrixMin FROM Objet             
                    ORDER BY ItemID 
                    DESC LIMIT ' . $premierMessageAafficher . ', ' . $nb_mess_per_page . '');

            echo "<h1> Liste des objets mis en vente </h1>";

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
            for ($i = 1 ; $i <= $nombreDePages ; $i++) {
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



