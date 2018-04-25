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
       <title>Profil des vendeurs</title>
   </head>

   <body>
        <header>
            <?php include("menu.php"); ?>
        </header>

        <?php

        if (isset($_GET['page']) and isset($_GET['SellerID'])) { 
            // on affiche le profil
            
            $req2 = $bdd->prepare('SELECT * FROM Vendeur, Utilisateur
                                    WHERE Vendeur.SellerID = Utilisateur.UserID
                                    AND Vendeur.SellerID = ?');
            $req2->execute(array($_GET['SellerID']));
            $profil = $req2->fetch();           
            $req2->closeCursor();

            echo "<h2> Profil du vendeur</h2>" . "<br>";

            echo "<table>"; // Tableau

            // Lignes dans le tableau
            echo "<tr>" . "<th>ID</th>" . "<td>" . $profil['SellerID'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Nom</th>" . "<td>" . $profil['Nom'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Prénom</th>" . "<td>" . $profil['Prenom'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Pseudo</th>" . "<td>" . $profil['Pseudo'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Date de naissance</th>" . "<td>" . $profil['DateNaissance'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Adresse</th>" . "<td>" . $profil['Adresse'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Adresse mail</th>" . "<td>" . $profil['AdresseMail'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Description</th>" . "<td>" . $profil['Description_user'] . "</td>" . "</tr>";

            echo "</table>";


            $req3 = $bdd->prepare('SELECT Pseudo, Time, Rate, Commentaire 
                                    FROM Evaluation, Utilisateur
                                    WHERE Evaluation.Buyer = Utilisateur.UserID
                                    AND Evaluation.Seller = ?');
            $req3->execute(array($_GET['SellerID']));
            ?>
            
            <?php
            echo "<br>" . "<h2> Evaluations du vendeur </h2>" . "<br>";

            echo "<table>"; // Tableau

            echo "<tr>" . "<th>Pseudo de l'acheteur </th>" . "<th>Date d'évaluation</th>";
            echo "<th>Note</th>" . "<th>Commentaire</th>" . "</tr>";

            while ($eval = $req3->fetch()) {
                // Lignes dans le tableau
                echo "<tr>"."<td>" . $eval['Pseudo'] . "</td>".
                "<td>" . $eval['Time'] . "</td>" . 
                "<td>" . $eval['Rate'] . "</td>" .
                "<td>" . $eval['Commentaire'] . "</td>" . "</tr>";
            
            }
            $req3->closeCursor();
            echo "</table>";

            // affiche du lien pour retour
            if ($_GET['page'] != 0) {
                echo '<a href="profil_vendeurs.php?page=' . $_GET['page'] . '">
                <button class="button button1">Retour</button></a> ';
            }
            else {
                echo '<a href="accueil.php"> <button class="button button1">Retour</button></a> ';
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
            $req = $bdd->query('SELECT COUNT(*) AS nb_messages FROM Vendeur');
            $donnees = $req->fetch();
            $req->closeCursor();
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
            echo "<form class='form' action='profil_vendeurs.php' method='post'>";
            echo "<p>";
                echo "Nombre d'élements par page" . "<br>";
                echo "<input type='number' name='quantity' min='20' max='100'>";
				echo "<input type='submit' value='Valider' />";
			echo "</p>";

		    echo "</form>";

            // On calcule le numéro du premier message qu'on prend pour le LIMIT
            $premierMessageAafficher = ($page - 1) * $nb_mess_per_page;

            $req1 = $bdd->query('SELECT SellerID, Pseudo FROM Vendeur, Utilisateur   
                    WHERE Vendeur.SellerID = Utilisateur.UserID          
                    ORDER BY SellerID 
                    DESC LIMIT ' . $premierMessageAafficher . ', ' . $nb_mess_per_page . '');

            echo "<h1> Liste des vendeurs </h1>" . "<br>";

            echo "<ul class='cadre'>";
            while ($seller = $req1->fetch()) {
                $id = $seller['SellerID'];

                // Affichage du lien (Pseudo du vendeur)
                echo "<li class = \"item\"><a href=\"profil_vendeurs.php?page=" . 
                $page . "&SellerID=" . $id . "\" >" . "<p class='rcorners corner2'>".
                $seller['Pseudo'] . "</p>" . "</a></li>";
            }
            echo "<ul />";

            // Puis on fait une boucle pour écrire les liens vers chacune des pages
            
            $req1->closeCursor();

            echo "<br><br>";

            echo "<div class='cadre'>";

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

            echo "<div />";
        }

        ?>

   </body>
</html>



