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
        <h2> Liste des objets </h2> 

        <?php

        if (isset($_GET['page']) and isset($_GET['ItemID'])) {    
            // on affiche l'objet
        
            $req2 = $bdd->prepare('SELECT * FROM Vendeur, Utilisateur
                                    WHERE Vendeur.SellerID = Utilisateur.UserID
                                    AND Vendeur.SellerID = ?');
            $req2->execute(array($_GET['SellerID']));
            $profil = $req2->fetch();

            echo "ID : " . $profil['SellerID'] . "<br>";
            echo "Nom : " . $profil['Nom'] . "<br>";
            echo "Prénom : " . $profil['Prenom'] . "<br>";
            echo "Pseudo : " . $profil['Pseudo'] . "<br>";
            echo "Date de naissance : " . $profil['DateNaissance'] . "<br>";
            echo "Adresse : " . $profil["Adresse"] . "<br>";
            echo "Adresse mail : " . $profil["AdresseMail"] . "<br>";
            echo "Description : " . $profil["Description_user"] . "<br><br>";

            // affiche du lien pour retour
            echo '<a href="profil_vendeurs.php?page=' . $_GET['page'] . '">' . "Retour" . '</a> ';
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
            $req = $bdd->query('SELECT COUNT(*) AS nb_messages FROM Vendeur');
            $donnees = $req->fetch();
            $total = $donnees ['nb_messages'];

            // On calcule le nombre de pages à créer
            $nombreDePages  = ceil($total / $nb_mess_per_page);
            
            // Puis on fait une boucle pour écrire les liens vers chacune des pages
            echo 'Pages : ';
            for ($i = 1 ; $i <= $nombreDePages ; $i++) {
                echo '<a href="profil_vendeurs.php?page=' . $i . '">' . $i . '</a> ';
            }
            echo "<br><br>";
            echo '<a href="accueil.php">' . "Retour" . '</a> ';
            echo "<br><br>";

            // On calcule le numéro du premier message qu'on prend pour le LIMIT
            $premierMessageAafficher = ($page - 1) * $nb_mess_per_page;

            $req1 = $bdd->query('SELECT SellerID, Nom, Prenom FROM Vendeur             
                    ORDER BY SellerID 
                    DESC LIMIT ' . $premierMessageAafficher . ', ' . $nb_mess_per_page . '');

            while ($seller = $req1->fetch()) {
                $id = $seller['SellerID'];
                // print des liens
                echo '<a href="profil_vendeurs.php?page=' . $page . '&SellerID=' . $id . '">' 
                    . $seller['Nom'] . " " . $seller['Prenom'] . '</a> ' . "<br>";
            }
        }

        ?>

   </body>
</html>



