<?php include("function.php"); ?>

<nav id="menu">
    <div class="element_menu">
        <ul>
            <li><a href=accueil.php> <img src="png/ebay.png" alt="Logo" id="logo"> </a></li>
            <?php
              if(isset($_SESSION['pseudo'])){
                echo 
                "<div class=\"dropMenu\">
                    <a href=# class = \"text_menu\">" . $_SESSION['pseudo'] . "</a>
                    <div class=\"dropBtn\"><a href=\"deconnexion.php\" class = \"text_menu\">Se déconnecter</a> </div>
                </div>";             
                 
                echo "<li><a href=\"profil_vendeurs.php\" class = \"text_menu\">Vendeurs</a></li>";
                echo "<li><a href=\"liste_objets.php\" class = \"text_menu\">Objets</a></li>";

                if (isset($_SESSION['pseudo']) && isSeller($_SESSION['pseudo'])) {                

                    echo "<li><a href=\"ajoutObjet.php\" class = \"text_menu\">Vendre</a></li>";  
                }
        
                else
                {
                    echo "<li><a href=\"inscription_vendeur.php\" class = \"text_menu\">Devenir vendeur</a></li>";
                }

                if (isAdmin($_SESSION['pseudo'])) {
                  echo "<li><a href=\"adminHandling.php\" class = \"text_menu\">Droits administrateur</a></li>";  
                }

              }
              else{
                echo "<li class><a href=\"connexion.php \" class = \"text_menu\">Se connecter</a></li>";
                echo "<li><a href=\"inscription.php \" class = \"text_menu\">S'inscrire</a></li>";
              }

              echo "<li><a href=\"accueil.php\" class = \"text_menu\">Accueil</a></li>";

            ?>
            <li><a href=# class ="text_menu">Aide</a></li>
        </ul>
    </div>
</nav>
