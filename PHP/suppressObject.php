<?php
    session_start();  // On démarre la session
    include('database.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css">
        <title>Supprimer un compte</title>
    </head>

    <body>
        <header>
          <?php include("menu.php"); ?>
        </header>
   
        <?php

        if (isset($_SESSION['pseudo'])) {

            echo "<h1>Supprimer un compte</h1>";

            $error = false;
            if (isset($_POST['pseudo']) && !(isUser($_POST['pseudo']) && checkPassword($_SESSION['pseudo'], SHA1($_POST['password']))))
            {
                // echo "Erreur ! Les données fournies sont invalides.";
                echo '<p class="error">"Erreur ! Les données fournies sont invalides"</p>';
                $error = true;
            }

            if (!isset($_POST['pseudo']) || $error)
            {
            ?>
                <form action="suppressAccount.php" method="post">
                        <p>
                            Pseudo de l'utilisateur à supprimer:
                            <input type="text" name="pseudo" /> <br />

                            Entrez votre mot de passe pour confirmer la suppression :
                            <input type="password" name="password" /> <br />
                            <input type="submit" value="Confirmer" />
                        </p>

                </form>

            <?php
            }

            else
            {
                $pseudo = $_POST['pseudo'];

                if (isUser($pseudo) && checkPassword($_SESSION['pseudo'], SHA1($_POST['password'])))
                {
                    include("database.php");

                    $reqID = $bdd->prepare('SELECT UserID FROM Utilisateur 
                                        WHERE Pseudo = ?');
                    $reqId->execute(array($pseudo));
                    $id = $reqId->fetch();
                    $reqId->closeCursor();

                    echo $id['UserID'];

                    //RECUPERER ITEMID
                    $reqGetItems = $bdd->prepare('SELECT ItemID FROM Objet WHERE SellerID = ?');
                    $reqGetItems->execute(array($id['UserID']));
                    
                    while ($items = $reqGetItems->fetch())
                    {
                        echo $items['ItemID'] . "<br />";

                        //PROPOSITION
                        $reqProp = $bdd->prepare('DELETE FROM PropositionAchat WHERE Buyer = ? OR ItemID = ?');
                        $reqProp->execute(array($id['UserID'], $items['ItemID']));

                        $reqProp->closeCursor();
                    }

                    $reqGetItems->closeCursor();
                    
                    $reqGetItemsBis = $bdd->prepare('SELECT ItemID FROM Objet WHERE SellerID = ?');
                    $reqGetItemsBis->execute(array($id['UserID']));
                    
                    while ($items = $reqGetItemsBis->fetch()){
                        //OBJET
                        $reqObj = $bdd->prepare('DELETE FROM Objet WHERE SellerID = ?');
                        $reqObj->execute(array($id['UserID']));
                        $reqObj->closeCursor();
                    }

                    $reqGetItemsBis->closeCursor();

                    //EVALUATION
                    $reqEval = $bdd->prepare('DELETE FROM Evaluation WHERE Buyer = ? OR Seller = ?');
                    $reqEval->execute(array($id['UserID'], $id['UserID']));

                    $reqEval->closeCursor();

                    //MODIFICATION
                    $reqModif = $bdd->prepare('DELETE FROM Modification WHERE AdminID = ?');
                    $reqModif->execute(array($id['UserID']));

                    $reqModif->closeCursor();
                    
                    //SELLER
                    if (isSeller($pseudo))
                    {
                        $reqSeller = $bdd->prepare('DELETE FROM Vendeur WHERE SellerID = ?');
                        $reqSeller->execute(array($id['UserID']));
        
                        $reqSeller->closeCursor();

                    }

                    //ADMIN
                    if (isAdmin($pseudo))
                    {
                        $reqAdmin = $bdd->prepare('DELETE FROM Administrateur WHERE AdminID = ?');
                        $reqAdmin->execute(array($id['UserID']));
        
                        $reqAdmin->closeCursor();

                    }

                    //USER
                    $reqSuppress = $bdd->prepare('DELETE FROM Utilisateur WHERE UserID = ?');
                    $reqSuppress->execute(array($id['UserID']));

                    $reqSuppress->closeCursor();

                    // echo "Le compte de " . $pseudo . ' à été supprimé !';
                    echo '<p class="success">"Le compte de " . $pseudo . " à été supprimé !"</p>';
                
                }
            }
        } 
            
        else {
            echo "
            <p class=error>
            Vous n'avez pas accès à cette page hors connexion !
            </p>";
        }
    ?>
        
    </body>
    
</html>