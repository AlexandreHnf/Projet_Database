<?php 
    session_start();  // On démarre la session
    include('database.php');
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <link rel="stylesheet" href="css/style.css">
       <title>Profil des vendeurs</title>
   </head>

   <body>
        <?php

        if (isset($_GET['page']) and isset($_GET['SellerID'])) {    
            // on affiche le profil
            
            $req2 = $bdd->prepare('SELECT * FROM Vendeur, Utilisateur
                                    WHERE Vendeur.SellerID = Utilisateur.UserID
                                    AND Vendeur.SellerID = ?');
            $req2->execute(array($_GET['SellerID']));
            $profil = $req2->fetch();           

            echo "<h2> Profil du vendeur</h2>" . "<br>";

            echo "ID : " . $profil['SellerID'] . "<br>";
            echo "Nom : " . $profil['Nom'] . "<br>";
            echo "Prénom : " . $profil['Prenom'] . "<br>";
            echo "Pseudo : " . $profil['Pseudo'] . "<br>";
            echo "Date de naissance : " . $profil['DateNaissance'] . "<br>";
            echo "Adresse : " . $profil["Adresse"] . "<br>";
            echo "Adresse mail : " . $profil["AdresseMail"] . "<br>";
            echo "Description : " . $profil["Description_user"] . "<br><br>";

            $req3 = $bdd->prepare('SELECT Pseudo, Time, Rate, Commentaire 
                                    FROM Evaluation, Utilisateur
                                    WHERE Evaluation.Buyer = Utilisateur.UserID
                                    AND Evaluation.Seller = ?');
            $req3->execute(array($_GET['SellerID']));

            echo "<h2> Evaluations du vendeur </h2>" . "<br>";

            while ($eval = $req3->fetch()) {
                echo "Pseudo de l'acheteur : " . $eval['Pseudo'] . "<br>";
                echo "Date d'évaluation : " . $eval['Time'] . "<br>";
                echo "Note : " . $eval['Rate'] . "<br>";
                echo "Commentaire : " . $eval['Commentaire'] . "<br>";
                echo "=============================================" . "<br>";
            } 

            // affiche du lien pour retour
            echo '<a href="profil_vendeurs.php?page=' . $_GET['page'] . '">
            <button class="button button1">Retour</button></a> ';

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
            $next_page = ($page + 1) % $nombreDePages;
            $prev_page = ($page - 1) % $nombreDePages;
            if ($prev_page == 0) {$prev_page = $nombreDePages;}
            
            echo "<h2> Pages </h2>" . "<br>";
            // Puis on fait une boucle pour écrire les liens vers chacune des pages
            
            echo '<a class=\'page\' href="profil_vendeurs.php?page='.$prev_page.'">'.'<<'.'</a>';
            for ($i = 1 ; $i <= $nombreDePages ; $i++) {
                if ($i == $page) {
                    echo '<a class=\'active\' href="profil_vendeurs.php?page='.$i.'">'.$i .'</a>';
                }
                else {
                    echo '<a class=\'page\' href="profil_vendeurs.php?page='.$i.'">'.$i . '</a>';
                }
            }
            echo '<a class=\'page\' href="profil_vendeurs.php?page='.$next_page.'">'.'>>'.'</a>';

            echo "<br><br>";
            echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>';

            // On calcule le numéro du premier message qu'on prend pour le LIMIT
            $premierMessageAafficher = ($page - 1) * $nb_mess_per_page;

            $req1 = $bdd->query('SELECT SellerID, Nom, Prenom FROM Vendeur             
                    ORDER BY SellerID 
                    DESC LIMIT ' . $premierMessageAafficher . ', ' . $nb_mess_per_page . '');

            echo "<h2> Liste des vendeurs </h2>" . "<br>";

            while ($seller = $req1->fetch()) {
                $id = $seller['SellerID'];
                // echo '<a href="profil_vendeurs.php?page=' . $page . '&SellerID=' . $id . '">' 
                //     . $seller['Nom'] . " " . $seller['Prenom'] . '</a> ' . "<br>";

                // Affichage du lien (nom et prénom du vendeur)
                echo "<li class = \"item\"><a href=\"profil_vendeurs.php?page=" . 
                $page . "&SellerID=" . $id . "\" >" . "<p class='rcorners corner1'>".
                $seller['Nom'] . " " . $seller['Prenom']. "</p>" . "</a></li>";
            }
        }

        ?>

   </body>
</html>



