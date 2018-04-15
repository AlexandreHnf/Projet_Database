<?php
    session_start();  // On démarre la session
    session_destroy();
    header('location: accueil.php');
?>
