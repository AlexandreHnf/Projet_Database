<?php 
    session_start();  // On démarre la session
    include('database.php');
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>Vendre</title>
   </head>

   <body>
        <h2> Vendre </h2> 

        <?php

        if (isset($_GET['page']) and isset($_GET['SellerID'])) {    
            // on affiche le profil
        
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
            echo "Description : " . $profil["Description_user"];

            // affiche du lien pour retour
            echo '<a href="profil_vendeurs?page=' . $_GET['page'] . '">' . Retour . '</a> ';
        }

        else{ // On affiche la liste des vendeurs

            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            else { $page = 1; }


            // On met dans une variable le nombre de messages qu'on veut par page
            $nombreDeMessagesParPage = 100; // Nombre d'éléments par page
            // On récupère le nombre total de messages
            $req = $bdd->query('SELECT COUNT(*) AS nb_messages FROM Vendeur');
            $donnees = $req->fetch();
            $total = $donnees ['nb_messages'];

            // On calcule le nombre de pages à créer
            $nombreDePages  = ceil($totalDesMessages / $nombreDeMessagesParPage);
            
            // Puis on fait une boucle pour écrire les liens vers chacune des pages
            echo 'Page : ';
            for ($i = 1 ; $i <= $nombreDePages ; $i++) {
                echo '<a href="profil_vendeurs?page=' . $i . '">' . $i . '</a> ';
            }

            // On calcule le numéro du premier message qu'on prend pour le LIMIT
            $premierMessageAafficher = ($page - 1) * $nombreDeMessagesParPage;

            $req1 = $bdd->prepare('SELECT SellerID, Nom, Prenom FROM Vendeur 
                                    ORDER BY SellerID DESC LIMIT ' . ? . ', ' . ?);
            $req1->execute(array($premierMessageAafficher, $nombreDeMessagesParPage));
            while ($donnees = $req1->fetch()) {
                // print des liens
                echo '<a href="profil_vendeurs?page=' . $page . '">' 
                    . $donnees['Nom'] . " " . $donnees['Prenom'] . '</a> ';
            }
        }

   </body>
</html>



