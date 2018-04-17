<?php

function isSeller($pseudo)
{
    include("database.php");
    $test = $bdd->prepare(' SELECT v.SellerID 
                            FROM Vendeur v, Utilisateur u 
                            WHERE v.SellerID = u.UserID AND u.Pseudo = ?'
                        );

    $test->execute(array($pseudo));
    $res = $test->fetch();
    
    return isset($res['SellerID']);
}

function isAdmin($pseudo)
{
    include("database.php");
    $test = $bdd->prepare(' SELECT a.AdminID 
                            FROM Administrateur a, Utilisateur u 
                            WHERE a.AdminID = u.UserID AND u.Pseudo = ?'
                        );

    $test->execute(array($pseudo));
    $res = $test->fetch();
    
    return isset($res['AdminID']);
}

function isUser($pseudo)
{
    include("database.php");
    $test = $bdd->prepare(' SELECT UserID 
                            FROM Utilisateur
                            WHERE Pseudo = ?'
                        );

    $test->execute(array($pseudo));
    $res = $test->fetch();
    
    return isset($res['UserID']);
}

function checkPassword($pseudo, $hashed_password)
{
    $errors = array(); // liste d'erreurs (messages)
    
    if (empty($pseudo) or empty($hashed_password)) {
        $errors[] = "Vous n'avez pas complété tous les champs !";
    }

    include("database.php");

    $req = $bdd->prepare('SELECT Pseudo FROM Utilisateur 
                        WHERE Pseudo = ? AND MotDePasse = ?');
    $req->execute(array($pseudo, $hashed_password)); // avec mdp hashé
    $donnees = $req->fetch();

    $req->closeCursor(); // Termine le traitement de la requête

    return isset($donnees['Pseudo']);
}

?>