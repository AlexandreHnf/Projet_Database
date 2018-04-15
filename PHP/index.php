<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <title>Exemple Ebay</title>
        <meta charset="utf-8" />
    </head>
<body>
    <h2>Welcome !</h2>
    <?php 
    if (isset($_POST['Pseudo']) and isset($_POST['password'])) {
        if($_POST['password'] == "123") {
            $_SESSION['Pseudo'] = $_POST['Pseudo'];
        }
    }
    if (isset($_SESSION['Pseudo'])) {
        echo '<p> Bonjour '. $_SESSION['Pseudo'].'!</p>';
    }
    ?>
    
    <form method="post" action="index.php">
        <input type="text" name="Pseudo" />
        <input type="password" name="password" />
        <input type="hidden" value=1 name="flag"/>
        <input type="submit" value="Login" />
    </form>

    <p> Test  <a href="sql_example.php">SQL requests</a></p>
    <p> <a href="close_session.php">Close session</a></p>
    
</body>
</html>
