<?php 
    session_start();  // On démarre la session
    include('database.php');
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
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

            echo "<h2> Détails de l'objet </h2>";

            echo "Titre : " . $objet['Titre'] . "<br>";
            echo "Description : " . $objet['Description_obj'] . "<br>";
            echo "Date de mise en vente : " . $objet['DateMiseEnVente'] . "<br>";
            echo "Prix minimum : " . $objet['PrixMin'] . " €" . "<br>";
            echo "Date de vente : " . $objet['DateVente'] . "<br>";
            echo "Acheteur : " . $objet["Acheteur"] . "<br>";
            echo "Vendeur : " . $objet["Nom"] . " " . $objet['Prenom'] . "<br>";
            echo "Catégorie : " . $objet["Categorie"] . "<br><br>";

            // affiche du lien pour retour
            echo '<a href="liste_objets.php?page=' . $_GET['page'] . '">' . "Retour" . '</a> ';
        }

        else{ // On affiche la liste des vendeurs

            echo "<h2> Liste des objets mis en vente </h2>";

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
            
            // Puis on fait une boucle pour écrire les liens vers chacune des pages
            echo 'Pages : ';
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



