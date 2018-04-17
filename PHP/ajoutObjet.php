<?php 
	session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
    <head>
        <title>Mettre un article en vente </title>
        <meta charset="utf-8" />
    </head>

    <body>
        <?php
        if (!ISSET($_POST['categorie'])) 
        {
        ?>
        <form method="post" action="ajoutObjet.php">
            Titre de l'article : <input type="text" name="Titre" value="" /> <br />
            Prix minimal demandé : <input type="text" name="Prix" value="" /> <br />

            Description de l'article : <br />
            <textarea name="Description" rows="8" cols="45">
            </textarea> <br />

            <select name="categorie">
                <?php
                    include("database.php");
                    $categories = $bdd->query('SELECT * FROM Categorie');
                    while ($tmpCat = $categories->fetch())
                    {
                        echo "<option value=" . $tmpCat['Titre']. ">" . $tmpCat['Titre'] . "</option>";
                    }
                    $categories->closeCursor();
                ?>
            </select> <br />

            <input type="submit" value="Valider">
        </form>
        
        <?php
        }
        else 
        {
            include("database.php");
            $pseudo = $_SESSION['pseudo'];
            echo $pseudo;
            $seller = $bdd->prepare(' SELECT v.SellerID
                                    FROM Utilisateur u, Vendeur v
                                    WHERE v.SellerID = u.UserID AND u.Pseudo = ?
                                ');
            $seller->execute(array($pseudo));
            $tmpID = $seller->fetch();

            $req = $bdd->prepare('INSERT INTO Objet(Titre, Description_obj, DateMiseEnVente, PrixMin, SellerID, Categorie)
            VALUES(:Titre, :Description_obj, :DateMiseEnVente, :PrixMin, :SellerID, :Categorie)');
            
            echo '<br />' . $_POST['categorie'];

            
            $date = new DateTime();
            $date = $date->format('Y-m-d');

            $req->execute(array(
                'Titre' => $_POST['Titre'],
                'Description_obj' => $_POST['Description'],
                'DateMiseEnVente' => $date,
                'PrixMin' => $_POST['Prix'],
                'SellerID' => $tmpID['SellerID'],
                'Categorie' => $_POST['categorie']
            ));

            $seller->closeCursor();
            $req->closeCursor();
            
            header('location: succes.php');
            exit;
            
        }
        ?>

        <p> <a href="accueil.php"> Retour </a></p>

    </body>
    

</html>