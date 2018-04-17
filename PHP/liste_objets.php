<?php 
    session_start();  // On démarre la session
    include('database.php');
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <link rel="stylesheet" href="css/style.css">
       <title>Objets</title>
   </head>

   <body>

        <?php

        if (isset($_GET['page']) and isset($_GET['ItemID'])) {    
            // on affiche l'objet
        
            $req2 = $bdd->prepare('SELECT * FROM Objet, Utilisateur, Vendeur
                                    WHERE Objet.ItemID = ?
                                    AND Objet.SellerID = Vendeur.SellerID');
            $req2->execute(array($_GET['ItemID']));
            $objet = $req2->fetch();

            // affiche du lien pour retour
            echo '<class = \"item\"><a href="liste_objets.php?page=' . $_GET['page'] . '">' . "Retour" . '</a>';

            echo "<h2> Caractéristiques de l'objet </h2>";

            echo "Titre : " . $objet['Titre'] . "<br>";
            echo "Description : " . $objet['Description_obj'] . "<br>";
            echo "Date de mise en vente : " . $objet['DateMiseEnVente'] . "<br>";
            echo "Prix minimum : " . $objet['PrixMin'] . " €" . "<br>";
            echo "Date de vente : " . $objet['DateVente'] . "<br>";
            echo "Acheteur : " . $objet["Acheteur"] . "<br>";
            echo "Vendeur : " . $objet["Nom"] . " " . $objet['Prenom'] . "<br>";
            echo "Catégorie : " . $objet["Categorie"] . "<br><br>";

            echo "<h2> Les propositions d'achat </h2>";
            
            $req3 = $bdd->prepare('SELECT Pseudo, Time, price, accepted
                                    FROM Utilisateur, PropositionAchat
                                    WHERE PropositionAchat.ItemID = ?
                                    AND PropositionAchat.Buyer = Utilisateur.UserID');
            $req3->execute(array($_GET['ItemID']));
            $prop = $req3->fetch();

            echo "Pseudo de l'acheteur potentiel : " . $prop['Pseudo'] . "<br>";
            echo "Date : " . $prop['Time'] . "<br>";
            echo "Prix proposé : " . $prop['price'] . " €" . "<br>";
            if ($prop['price'] == True) {
                echo "Statut : accepté" . "<br>";
            }
            else {
                echo "Statut: refusé" . "<br>";
            }
            echo "========================================================" . "<br>";

        }

        else{ // On affiche la liste des vendeurs

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            else { 
                $page = 1; // par défaut
            }


            // On met dans une variable le nombre de messages qu'on veut par page
            $nb_mess_per_page = 100; // Nombre d'éléments par page
            // On récupère le nombre total de messages
            $req = $bdd->query('SELECT COUNT(*) AS nb_messages FROM Objet');
            $donnees = $req->fetch();
            $total = $donnees ['nb_messages'];

            // On calcule le nombre de pages à créer
            $nombreDePages  = ceil($total / $nb_mess_per_page);
            
            echo "<h2> Pages </h2>";
            // Puis on fait une boucle pour écrire les liens vers chacune des pages

            for ($i = 1 ; $i <= $nombreDePages ; $i++) {
                echo '<a href="liste_objets.php?page=' . $i . '">' . $i . '</a> ';
            }
            echo "<br><br>";
            echo '<a href="accueil.php">' . "Retour" . '</a> ';
            echo "<br><br>";

            // On calcule le numéro du premier message qu'on prend pour le LIMIT
            $premierMessageAafficher = ($page - 1) * $nb_mess_per_page;

            $req1 = $bdd->query('SELECT ItemID, Titre, PrixMin FROM Objet             
                    ORDER BY ItemID 
                    DESC LIMIT ' . $premierMessageAafficher . ', ' . $nb_mess_per_page . '');

            echo "<h2> Liste des objets mis en vente </h2>";

            while ($obj = $req1->fetch()) {
                $id = $obj['ItemID'];
                // print des liens
                echo '<a href="liste_objets.php?page=' . $page . '&ItemID=' . $id . '">' 
                    . $obj['Titre'] . " | Prix: " . $obj['PrixMin'] . " €" . '</a> ' . "<br>";
            }
        }

        ?>

   </body>
</html>



