<?php 
    session_start();  // On démarre la session
    include("database.php");
    include("function.php");
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <link rel="stylesheet" href="css/style.css">
       <title>Requetes</title>
   </head>

   <body>

        <header>
            <?php include("menu.php"); ?>
        </header>


        <?php

        if (isset($_GET['r']) && $_GET['r'] == 1) {
            echo "<h3>REQUETE 1: Les vendeurs qui sont appréciés par les utilisateurs ayant les même goûts que l'utilisateur 'Jules'</h3> <br>";

            $req = $bdd->query('SELECT DISTINCT v1.SellerID AS ID
                                   FROM Vendeur v1, Evaluation e1, Utilisateur u1
                                   WHERE e1.Seller = v1.SellerID AND u1.UserID = e1.Buyer AND e1.Rate >= 3 AND u1.UserID in 
                                        (SELECT u2.UserID
                                         FROM Vendeur v2, Evaluation e2, Utilisateur u2
                                         WHERE u2.UserID = e2.Buyer AND v2.SellerID = e2.Seller AND e2.Rate > 3 AND
                                         v2.SellerID in
                                            (SELECT v3.SellerID
                                             FROM Vendeur v3, Utilisateur u3, Evaluation e3
                                             WHERE e3.Seller = v3.SellerID AND e3.Buyer = u3.UserID
                                             AND u3.Pseudo = "theJules" AND e3.Rate > 3))');
                                  

        }

        if (isset($_GET['r']) && $_GET['r'] == 2) {
            echo "<h3>REQUETE 2: Les vendeurs ayant vendu et acheté à la même personne</h3>";

            $req = $bdd->query('SELECT DISTINCT v.SellerID AS ID
                                 FROM Vendeur v, Objet o1
                                 WHERE v.SellerID = o1.SellerID AND o1.Acheteur IN
                                     (SELECT DISTINCT v2.SellerID
                                      FROM Vendeur v2, Objet o2
                                      WHERE v2.SellerID = o2.SellerID AND v.SellerID = o2.Acheteur
                                      )');

        }

        if (isset($_GET['r']) && $_GET['r'] == 3) {
            echo "<h3>REQUETE 3: Les objets vendus à un prix inférieur au montant d'une proposition d'achat de cet objet</h3>";

            $req = $bdd->query('SELECT DISTINCT o.ItemID AS ID
                                 FROM Objet o, PropositionAchat p
                                 WHERE o.ItemID = p.ItemID AND p.accepted = "True" AND
                                    EXISTS (SELECT *
                                    FROM PropositionAchat p1
                                    WHERE p1.ItemID = p.ItemID AND p1.price > p.price AND p1.accepted = "False"
                                    )');

        }

        if (isset($_GET['r']) && $_GET['r'] == 4) {
            echo "<h3>REQUETE 4: Le/les vendeurs ayant vendu le plus d'objets dans la même catégorie</h3>";

            $req = $bdd->query('SELECT v.SellerID AS ID
                                FROM Vendeur v, Categorie c 
                                WHERE ( SELECT COUNT(*)
                                FROM Objet o
                                WHERE v.SellerID = o.SellerID AND o.Categorie = c.Titre)

                                >=
    
                                (SELECT MAX(tot_count.sold)
                                FROM (SELECT COUNT(*) as sold, c1.Titre as cat 
                                      FROM Objet o1, Vendeur v1, Categorie c1 
                                      WHERE v1.SellerID = o1.SellerID AND o1.Categorie = c1.Titre 
                                      GROUP BY v1.SellerID ORDER BY COUNT(*) DESC) tot_count 
                                      WHERE cat = c.Titre)');
        }

        if (isset($_GET['r']) && $_GET['r'] == 5) {
            echo "<h3>REQUETE 5: Les objets pour lesquels au moins dix propositions d'achats ont été refusées</h3>";

            $req = $bdd->query('SELECT p.ItemID AS ID
                                 FROM PropositionAchat p
                                 WHERE p.accepted = "False"
                                 GROUP BY p.ItemID
                                 HAVING COUNT(*) >= 10');

        }

        if (isset($_GET['r']) && $_GET['r'] == 6) {
            echo "<h3>REQUETE 6: Les vendeurs avec le nombre moyen d'objets vendus, le nombre moyen d'objet achetés, la note qu'ils ont
            reçue, et la moyenne des notes qu'ils ont donné, et ce pour tout les vendeurs ayant vendu et acheté au moins
            10 objets</h3>";








            echo "<table>"; // Tableau

            echo "<tr>" . "<th>Vendeurs </th>" . "<th>Moy_Nb_obj_vendus</th>";
            echo "<th>nb_moy_obj_achetés</th>" . "<th>notes_recues</th>" .
            "<th>moy_notes_données</th>" . "</tr>";
        }

        
        echo "<ul class='cadre'>";

        $count = 0;
        while ($requete = $req->fetch()) {
            $id = $requete['ID'];
            // print des liens

            if (isset($_GET['r'])) {
                if ($_GET['r'] == 1 or $_GET['r'] == 2
                    or $_GET['r'] == 4) {

                    echo "<li class = \"item\"><a href=\"profil_vendeurs.php?page=0&SellerID=" . 
                    $id . "\" >" . "<p class='rcorners corner2'>
                    ".$id. "</p>" . "</a></li>";
                }

                elseif ($_GET['r'] == 3 or $_GET['r'] == 5) {
                    echo "<li class = \"item\"><a href=\"liste_objets.php?page=0&ItemID=" . 
                    $id . "\" >" . "<p class='rcorners corner2'>
                    ".$id. "</p>" . "</a></li>";
                }

                else {
                    // Lignes dans le tableau
                    echo "<tr>"."<td>" . "<a href=\"profil_vendeurs.php?page=0&SellerID=" . 
                    $id . "\" > " . $id . "</a>" . "</td>" .

                    "<td>" . $requete['Moy_Nb_obj_vendus'] . "</td>" . 
                    "<td>" . $requete['nb_moy_obj_achetés'] . "</td>" .
                    "<td>" . $requete['notes_recues'] . "</td>" . "</tr>" .
                    "<td>" . $requete['moy_notes_données'] . "</td>" . "</tr>";
                    
                }       
            }
            $count++;
        }

        if (isset($_GET['r']) && $_GET['r'] == 6) { echo "</table>";}
        
        echo "<h3> Nombre de résultats: " . $count . "</h3>";
        $req->closeCursor();

        ?>

   </body>
</html>
