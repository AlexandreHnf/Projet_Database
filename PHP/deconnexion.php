<?php
    session_start();  // On démarre la session
    session_destroy();
    header('Location: accueil.php');
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>deconnexion</title>
  </head>
  <body>
  </body>
</html>
