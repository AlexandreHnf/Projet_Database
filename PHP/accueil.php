<?php 
    session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Accueil</title>
    </head>

    <body>
        <h2>Accueil</h2>

        <?php

        if (isset($_SESSION['pseudo'])) {
            echo '<p> Bonjour '. htmlspecialchars($_SESSION['pseudo']).'!</p>';
        }

        include("database.php");
        $req3 = $bdd->prepare('SELECT Pseudo, AdresseMail FROM Utilisateur WHERE Pseudo = ?');
        $req3->execute(array('a'));
        $donnees3 = $req3->fetch();

        if ($donnees3) {
            echo "mail de a: " . $donnees3['AdresseMail'] . "<br>";
        }

        $req4 = $bdd->prepare('SELECT Nom, Prenom FROM Vendeur WHERE Nom = ?');
        $req4->execute(array('a3'));
        $donnees4 = $req4->fetch();

        if ($donnees4) {
            echo "nom et prenom du vendeur a3: " . $donnees4['Nom'] . " " . $donnees4['Prenom'];
        }

        if (isset($_SESSION['pseudo']) && isSeller($_SESSION['pseudo'])) {
            $req5 = $bdd->prepare(' SELECT v.SellerID 
                                    FROM Vendeur v, Utilisateur u 
                                    WHERE v.SellerID = u.UserID AND u.Pseudo = ?'
                                );
            $req5->execute(array($_SESSION['pseudo']));
            $donnee5 = $req5->fetch();
        ?>
        <p> <a href="ajoutObjet.php">Mettre un objet en vente</a></p>
        <?php   
        }

        else
        {
        ?>
        <p> <a href="inscription_vendeur.php">Devenir vendeur !</a></p>
        <?php
        }
        ?>
        <p> <a href="deconnexion.php">Se déconnecter</a></p>
    </body>
</html>


<?php

function isSeller($pseudo)
{
    include("database.php");
    $test = $bdd->prepare(' SELECT v.SellerID 
                            FROM Vendeur v, Utilisateur u 
                            WHERE v.SellerID = u.UserID AND u.Pseudo = ?'
                        );

    $test->execute(array($_SESSION['pseudo']));
    $res = $test->fetch();
    
    return isset($res['SellerID']);
}

?>