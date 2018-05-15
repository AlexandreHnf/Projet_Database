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
       <title>Modification des informations personnelles</title>
   </head>

   <body>
        <header>
            <?php include("menu.php"); ?>
        </header>

        <h1>Modification des informations personnelles</h1>

        <a href="accueil.php">
        <button class="button button1">Retour</button></a><br><br>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Vérification du mot de passe de confirmation
            $hashed_mdp = SHA1($_POST['old_mdp']);
            $reqCheckAccount = $bdd->prepare('SELECT UserID FROM Utilisateur 
								WHERE Pseudo = ? AND MotDePasse = ?');
            $reqCheckAccount->execute(array($_SESSION['pseudo'], $hashed_mdp));
            $donnees = $reqCheckAccount->fetch();
            $id = $donnees['UserID'];

            if (!$donnees) {
                //Pas le bon mdp
                echo '<p class="error">'."Mot de passe de confirmation
                invalide !".'</p>';
            }

            else {

                if (!empty($_POST['pseudo'])) {
                    $pseudo = $_POST['pseudo'];
                    $_SESSION['pseudo'] = $pseudo;  
                    //mise à jour pseudo de session aussi
                }

                else {$pseudo = $_SESSION['pseudo'];}

                if (!empty($_POST['new_mdp'])) {$mdp  = $_POST['new_mdp'];}
                else {
                    $req1 = $bdd->prepare('SELECT MotDePasse FROM Utilisateur WHERE UserID = ?');
                    $req1->execute(array($id));
                    $res1 = $req1->fetch();
                    $mdp = $res1['MotDePasse'];
                }

                if (!empty($_POST['email'])) {$email = $_POST['email'];}
                else {
                    $req2 = $bdd->prepare('SELECT AdresseMail FROM Utilisateur WHERE UserID = ?');
                    $req2->execute(array($id));
                    $res2 = $req2->fetch();
                    $email = $res2['AdresseMail'];
                }

                if (!empty($_POST['desc'])) {$desc = $_POST['desc'];}
                else {
                    $req3 = $bdd->prepare('SELECT Description_user FROM Utilisateur WHERE UserID = ?');
                    $req3->execute(array($id));
                    $res3 = $req3->fetch();
                    $desc = $res3['Description_user'];
                }

                //Mise à jour des infos utilisateur
                $requpdateUser = $bdd->prepare('UPDATE Utilisateur
                SET  Pseudo = ?, MotDePasse = ?, AdresseMail = ?, Description_user = ?
                WHERE UserID = ?');
                $requpdateUser->execute(array(
                    $pseudo, $mdp, $email, $desc, $id
                ));
                $requpdateUser->closeCursor();

                //Si vendeur
                if ($_SESSION['isSeller'] == true) {
                    if (!empty($_POST['name'])) {$name = $_POST['name'];}
                    else {
                        $req4 = $bdd->prepare('SELECT Nom FROM Vendeur WHERE SellerID = ?');
                        $req4->execute(array($id));
                        $res4 = $req4->fetch();
                        $name = $res4['Nom'];
                    }

                    if (!empty($_POST['firstname'])) {$firstname = $_POST['firstname'];}
                    else {
                        $req5 = $bdd->prepare('SELECT Prenom FROM Vendeur WHERE SellerID = ?');
                        $req5->execute(array($id));
                        $res5 = $req5->fetch();
                        $firstname = res5['Prenom'];
                    }

                    if (!empty($_POST['adresse'])) {$adresse = $_POST['adresse'];}
                    else {
                        $req6 = $bdd->prepare('SELECT Adresse FROM Vendeur WHERE SellerID = ?');
                        $req6->execute(array($id));
                        $res6 = $req6->fetch();
                        $adresse = $res6['Adresse'];
                    }

                    if (!empty($_POST['date'])) {$date = $_POST['date'];}
                    else {
                        $req7 = $bdd->prepare('SELECT DateNaissance FROM Vendeur WHERE SellerID = ?');
                        $req7->execute(array($id));
                        $res7 = $req7->fetch();
                        $date = $res7['DateNaissance'];
                    }

                    //Mise à jour des infos vendeur
                    $requpdateVendeur = $bdd->prepare('UPDATE Vendeur
                    SET  Nom = ?, Prenom = ?, DateNaissance = ?, Adresse = ?
                    WHERE SellerID = ?');
                    $requpdateVendeur->execute(array(
                        $name, $firstname, $date, $adresse, $id
                    ));
                    $requpdateVendeur->closeCursor();
    
                }

                echo "Informations personnelles modifiées avec succès";


            }


        }

        if (!($_SERVER['REQUEST_METHOD'] == 'POST') || !$id) {
            ?>
            
            <br />
            <form class='form' action="modProfile.php" method="post">
                Pseudo : <input type="text" placeholder="Pseudo" name="pseudo"><br /><br />
                Mot de passe : <input type="password" placeholder="Nouveau mot de passe" name="new_mdp"><br /><br />
                Adresse E-mail: <input type="email" placeholder="exemple@a.be" name="email" /><br /><br />
                Description: <br />
                <textarea name="desc" rows="8" cols="30">
                </textarea> <br /><br />

                <?php
                if ($_SESSION['isSeller'] == true) {
                    ?>
                        Nom : <input type="text" placeholder="Nom" name="name"><br /><br />
                        Prénom : <input type="text" placeholder="Prénom" name="firstname"><br /><br />
                        Adresse : <input type="text" placeholder="Adresse" name="adresse"><br /><br />
                        Date de naissance: <input type="date" name="date" /><br /><br />
                    <?php
                }
                ?>

                Entrez votre mot de passe pour confirmer: <br />
                <input type="password" placeholder="Mot de passe" name="old_mdp" /><br />
                
                <br />
                <input type="submit" value="Enregistrer les changements" />
            </form>

            <?php
        }
        ?>
    </body>
</html>


