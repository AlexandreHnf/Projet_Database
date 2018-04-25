<?php 
	session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
    <head>
        <title>Mettre un article en vente </title>
        <link rel="stylesheet" href="css/style.css">
        <meta charset="utf-8" />
    </head>

    <body>
        <header>
            <?php include("menu.php"); ?>
        </header>
        
        <h2>Vendre !</h2>

        <?php
        if (!ISSET($_POST['categorie'])) 
        {
        ?>
        <form class='form' method="post" action="ajoutObjet.php">
            Titre de l'article : <br />
            <input type="text" name="Titre" placeholder="Titre" /> <br /><br />
            Prix minimal demandé : <br />
            <input type="float" name="Prix" placeholder="Prix en €" /> <br /><br />

            Description de l'article : <br />
            <textarea name="Description" rows="8" cols="45">
            </textarea> <br /><br />

            Catégorie : <br />

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
            </select> <br /><br />

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

        <?php echo "<br>"; ?>
		<?php echo '<a href="accueil.php">
            <button class="button button1">Retour</button></a> ' . '<br><br>'; ?>

    </body>
    

</html>