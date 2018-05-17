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
            echo "Les vendeurs qui sont appréciés par les utilisateurs ayant les même goûts que l'utilisateur 'Jules' <br>";

            $req1 = $bdd->query('SELECT DISTINCT v1.SellerID
                                   FROM Vendeur v1, Evaluation e1, Utilisateur u1
                                   WHERE e1.Seller = v1.SellerID AND u1.UserID = e1.Buyer AND e1.Rate >= 3 AND u1.UserID in 
                                        (SELECT u2.UserID
                                         FROM Vendeur v2, Evaluation e2, Utilisateur u2
                                         WHERE u2.UserID = e2.Buyer AND v2.SellerID = e2.Seller AND e2.Rate > 3 AND
                                         v2.SellerID in
                                            (SELECT v3.SellerID
                                             FROM Vendeur v3, Utilisateur u3, Evaluation e3
                                             WHERE e3.Seller = v3.SellerID AND e3.Buyer = u3.UserID
                                             AND u3.UserID = 41 AND e3.Rate > 3))');
                                             
            $r1 = $req1->fetch();

        }

        if (isset($_GET['r']) && $_GET['r'] == 2) {
            echo "<h2>Les vendeurs ayant vendu et acheté à la même personne</h2>";

            $req2 = $bdd->query('SELECT DISTINCT v.SellerID
                                 FROM Vendeur v, Objet o1
                                 WHERE v.SellerID = o1.SellerID AND o1.Acheteur IN
                                     (SELECT DISTINCT v2.SellerID
                                      FROM Vendeur v2, Objet o2
                                      WHERE v2.SellerID = o2.SellerID AND v.SellerID = o2.Acheteur
                                      )');

            $r2 = $req2->fetch();
        }

        if (isset($_GET['r']) && $_GET['r'] == 3) {
            echo "<h2>Les objets vendus à un prix inférieur au montant d'une proposition d'achat de cet objet</h2>";

            $req3 = $bdd->query('SELECT DISTINCT o.ItemID
                                 FROM Objet o, PropositionAchat p
                                 WHERE o.ItemID = p.ItemID AND p.accepted = 'True' AND
                                    EXISTS (SELECT *
                                    FROM PropositionAchat p1
                                    WHERE p1.ItemID = p.ItemID AND p1.price > p.price AND p1.accepted = 'False'
                                    )');

            $r3 = $req3->fetch();
        }

        if (isset($_GET['r']) && $_GET['r'] == 4) {
            echo "<h2>Le/les vendeurs ayant vendu le plus d'objets dans la même catégorie</h2>";
        }

        if (isset($_GET['r']) && $_GET['r'] == 5) {
            echo "<h2>Les objets pour lesquels au moins dix propositions d'achats ont été refusées</h2>";

            $req5 = $bdd->query('SELECT p.ItemID
                                 FROM PropositionAchat p
                                 WHERE p.accepted = "False"
                                 GROUP BY p.ItemID
                                 HAVING COUNT(*) >= 10');

            $r5 = $req5-fetch();
        }

        if (isset($_GET['r']) && $_GET['r'] == 6) {
            echo "<h2>Les vendeurs avec le nombre moyen d'objets vendus, le nombre moyen d'objet achetés, la note qu'ils ont
            reçue, et la moyenne des notes qu'ils ont donné, et ce pour tout les vendeurs ayant vendu et acheté au moins
            10 objets</h2>";
        }

        ?>

   </body>
</html>
