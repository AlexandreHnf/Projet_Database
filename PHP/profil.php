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
       <title>Profil</title>
   </head>

   <body>
        <header>
            <?php include("menu.php"); ?>
        </header>

        <?php

        if (isset($_SESSION['pseudo'])) {    
            // on affiche le profil
            $isSeller = false;
            $isAdmin = false;

            // VENDEUR
            if (isset($_SESSION['isSeller']) && $_SESSION['isSeller']) {
                $isSeller = true;
                $req1 = $bdd->prepare('SELECT * FROM Vendeur, Utilisateur
                                        WHERE Vendeur.SellerID = Utilisateur.UserID
                                        AND Utilisateur.Pseudo = ?');
                $req1->execute(array($_SESSION['pseudo']));
                $profil = $req1->fetch();           
                $req1->closeCursor();
            }

            else {                              // SIMPLE UTILISATEUR
                $req1 = $bdd->prepare('SELECT * FROM Utilisateur 
                                        WHERE Utilisateur.Pseudo = ?');
                $req1->execute(array($_SESSION['pseudo']));
                $profil = $req1->fetch();           
                $req1->closeCursor();
            }
            $id = $profil['UserID'];

            // ADMIN
            if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
                $isAdmin = true;
            }

            echo "<h1> Votre profil</h1>" . "<br>";

            // rôle= Utilisateur, vnedeur, admin
            echo "<table>";
            echo "<tr>" . "<th>Rôle(s)</th>" . "</tr>";
            echo "<tr>" . "<td>Utilisateur</td>" . "</tr>";
            if ($isSeller) {echo "<tr>" . "<td>Vendeur</td>" . "</tr>";}
            if ($isAdmin) {echo "<tr>" . "<td>Administrateur</td>" . "</tr>";}
            echo "</table>" . "<br>";


            echo "<table>"; // Tableau

            // Lignes dans le tableau
            echo "<tr>" . "<th>ID</th>" . "<td>" . $id . "</td>" . "</tr>";
            echo "<tr>" . "<th>Pseudo</th>" . "<td>" . $profil['Pseudo'] . "</td>" . "</tr>";

            if ($isSeller) {
                echo "<tr>" . "<th>Nom</th>" . "<td>" . $profil['Nom'] . "</td>" . "</tr>";
                echo "<tr>" . "<th>Prénom</th>" . "<td>" . $profil['Prenom'] . "</td>" . "</tr>";
                echo "<tr>" . "<th>Date de naissance</th>" . "<td>" . $profil['DateNaissance'] . 
                    "</td>" . "</tr>";
                echo "<tr>" . "<th>Adresse</th>" . "<td>" . $profil['Adresse'] . "</td>" . "</tr>";
            }

            echo "<tr>" . "<th>Adresse mail</th>" . "<td>" . $profil['AdresseMail'] . "</td>" . "</tr>";
            echo "<tr>" . "<th>Description</th>" . "<td>" . $profil['Description_user'] . "</td>" . "</tr>";

            echo "</table>";

            // =======================================================================

            //objets

            // ====================== EVALUATIONS FAITES AUX VENDEURS ====================
            $req2 = $bdd->prepare('SELECT Time, Rate, Commentaire, Nom, Prenom 
                                    FROM Evaluation, Utilisateur, Vendeur
                                    WHERE Evaluation.Buyer = Utilisateur.UserID
                                    AND Evaluation.Seller = Vendeur.SellerID
                                    AND Utilisateur.UserID = ?');
            $req2->execute(array($id));

            echo "<br>" . "<h1> Vos évaluations </h1>" . "<br>";

            echo "<table>"; // Tableau

            echo "<tr>" . "<th>Vendeur évalué </th>" . "<th>Date d'évaluation</th>";
            echo "<th>Note</th>" . "<th>Commentaire</th>" . "</tr>";

            while ($eval_perso = $req2->fetch()) {
                // Lignes dans le tableau
                echo "<tr>"."<td>" . $eval_perso['Nom'] . " " . $eval_perso['Prenom'] ."</td>".
                "<td>" . $eval_perso['Time'] . "</td>" . 
                "<td>" . $eval_perso['Rate'] . "</td>" .
                "<td>" . $eval_perso['Commentaire'] . "</td>" . "</tr>";
            
            }
            $req2->closeCursor();
            echo "</table>";


            // ==================== ETANT VENDEUR, EVALUATIONS RECUES =====================
            $req3 = $bdd->prepare('SELECT Pseudo, Time, Rate, Commentaire 
                                    FROM Evaluation, Utilisateur
                                    WHERE Evaluation.Buyer = Utilisateur.UserID
                                    AND Evaluation.Seller = ?');
            $req3->execute(array($id));
            

            
            echo "<br>" . "<h1> Evaluations reçues </h1>" . "<br>";

            echo "<table>"; // Tableau

            echo "<tr>" . "<th> Pseudo de Acheteur </th>" . "<th>Date d'évaluation</th>";
            echo "<th>Note</th>" . "<th>Commentaire</th>" . "</tr>";

            while ($eval = $req3->fetch()) {
                // Lignes dans le tableau
                echo "<tr>"."<td>" . $eval['Pseudo'] ."</td>".
                "<td>" . $eval['Time'] . "</td>" . 
                "<td>" . $eval['Rate'] . "</td>" .
                "<td>" . $eval['Commentaire'] . "</td>" . "</tr>";
            
            }
            $req3->closeCursor();
            echo "</table>";


            // ==================== OBJETS MIS EN VENTE PAR LUI ==================================

            $req4 = $bdd->prepare('SELECT * FROM Objet 
                                        WHERE Objet.SellerID = ?');
            $req4->execute(array($id));

            echo "<br>" . "<h1> Vos objets mis en vente </h1>" . "<br>";

            echo "<table>"; // Tableau

            echo "<tr>" . "<th>Titre </th>" . "<th>Prix</th>";

            while ($obj = $req4->fetch()) {
                // Lignes dans le tableau
                echo "<tr>"."<td>" . $obj['Titre'] . "</td>".
                "<td>" . $obj['PrixMin'] . " €" . "</td>" . "</tr>";
            
            }
            $req4->closeCursor();
            echo "</table>";



            // ==================== PROPOSITIONS D'ACHAT QU'IL A FAIT =====================
            $req5 = $bdd->prepare('SELECT Titre, Time, price, accepted
                                    FROM PropositionAchat, Objet
                                    WHERE Objet.ItemID = PropositionAchat.ItemID
                                    AND Buyer = ?');
            $req5->execute(array($id));            

            
            echo "<br>" . "<h1> Vos propositions d'achat </h1>" . "<br>";

            echo "<table>"; // Tableau

            echo "<tr>" . "<th> Objet concerné </th>" . "<th>Date</th>";
            echo "<th>Prix</th>" . "<th>Statut</th>" . "</tr>";

            while ($prop = $req5->fetch()) {
                // Lignes dans le tableau
                echo "<tr>"."<td>" . $prop['Titre'] ."</td>".
                "<td>" . $prop['Time'] . "</td>" . 
                "<td>" . $prop['price'] . " €" . "</td>";
                if (isset($prop['accepted'])) {
                    if ($prop['accepted'] == True) {
                        echo "<td>accepté </td>" . "</tr>";
                    }
                    else {
                        echo "<td>refusé</td>" . "</tr>";
                    }
                }
            
            }
            $req4->closeCursor();
            echo "</table>";

            // affiche du lien pour retour
            echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ';

        }

        ?>

   </body>
</html>



