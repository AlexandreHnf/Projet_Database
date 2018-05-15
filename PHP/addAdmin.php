<?php
    session_start();  // On démarre la session
    include('database.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css">
        <title>Ajout d'un administrateur</title>
    </head>

    <body>
        <header>
          <?php include("menu.php"); ?>
        </header>
        <h1>Ajouter un administrateur</h1>
    <?php
        $error = false;
        if (isset($_POST['newAdmin']) && !(!isAdmin($_POST['newAdmin']) 
            && isUser($_POST['newAdmin']) 
            && checkPassword($_SESSION['pseudo'], SHA1($_POST['password']))))
        {
            echo "Erreur ! Les données fournies sont invalides.";
            $error = true;
        }

        if (!isset($_POST['newAdmin']) || $error)
        {
        ?>
            <form class="cadre" action="addAdmin.php" method="post">
                    <p>
                        Pseudo de l'utilisateur à ajouter: <br />
                        <input type="text" name="newAdmin" /> <br /><br />

                        Entrez votre mot de passe pour confirmer l'ajout : <br />
                        <input type="password" name="password" /> <br /><br />
                        <input type="submit" value="Ajouter" /> <br />
                    </p>

            </form>

        <?php
        }

        else
        {
            $newAdmin = $_POST['newAdmin'];

            if (!isAdmin($newAdmin) && isUser($newAdmin) 
                && checkPassword($_SESSION['pseudo'], SHA1($_POST['password'])))
            {
                include("database.php");

                $req = $bdd->prepare('SELECT UserID FROM Utilisateur 
                                    WHERE Pseudo = ?');
                $req->execute(array($newAdmin));
                $id = $req->fetch();
                $req->closeCursor(); // Termine le traitement de la requête
                

                $req1 = $bdd->prepare('INSERT INTO Administrateur(AdminID)
                VALUES(:AdminID)');
                $req1->execute(array(
                    'AdminID' => $id['UserID']
                ));

                $req1->closeCursor();

                echo $newAdmin . ' à été ajouté aux administrateurs !';
            
            }          

        }
?>