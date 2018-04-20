<?php include("function.php"); ?>

<nav id="menu">
    <div class="element_menu">
        <ul>
            <li><a href=accueil.php> <img src="png/ebay.png" alt="Logo" id="logo"> </a></li>
            <?php
              if(isset($_SESSION['pseudo'])){
				// PROFIL
                echo "<li><a class = 'menu' href=# class = \"text_menu\">" . $_SESSION['pseudo'] . "</a></li>";  
                // echo '<a class = \'menu\' href="accueil.php">
                // <button class="button button1">' . $_SESSION['pseudo'] . '</button></a> ' . '<br><br>';
				
				// Liste des vendeurs et leurs profils
				echo "<li><a class = 'menu' href=\"profil_vendeurs.php\" class = \"text_menu\">Vendeurs</a></li>";
				
				// Liste des objets mis en vente + leurs caractéristiques
                echo "<li><a class = 'menu' href=\"liste_objets.php\" class = \"text_menu\">Objets</a></li>";

                if (isset($_SESSION['pseudo']) && isSeller($_SESSION['pseudo'])) {                
					// Vendre un objet en étant vendeur
                    echo "<li><a class = 'menu' href=\"ajoutObjet.php\" class = \"text_menu\">Vendre</a></li>";  
                }
        
                else{
					// Devenir un vendeur => formulaire
                    echo "<li><a class = 'menu' href=\"inscription_vendeur.php\" class = \"text_menu\">Devenir vendeur</a></li>";
                }

                if (isAdmin($_SESSION['pseudo'])) {
				  // Modifier les informations en étant admin
                  echo "<li><a class = 'menu' href=\"adminHandling.php\" class = \"text_menu\">Droits administrateur</a></li>";  
                }

				// deconnexion
                echo "<li><a class = 'menu' href=\"deconnexion.php\" class = \"text_menu\">Se déconnecter</a></li>";
			  }
			  
              else{
				// Si l'utilisateur n'est pas connecté
                echo "<li class><a class = 'menu' href=\"connexion.php \" class = \"text_menu\">Se connecter</a></li>";
                echo "<li><a class = 'menu' href=\"inscription.php \" class = \"text_menu\">S'inscrire</a></li>";
              }

              echo "<li><a class = 'menu' href=\"accueil.php\" class = \"text_menu\">Accueil</a></li>";

            ?>
            <li><a class = 'menu' href=# class ="text_menu">Aide</a></li>
            <?php include("researchTool.php"); ?>
        </ul>
    </div>
</nav>
