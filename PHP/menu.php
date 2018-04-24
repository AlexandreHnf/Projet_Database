<?php include("function.php"); ?>

<nav>
  <div>
    <ul>
      <!-- <li><a class='link_menu' href=accueil.php> <img src="png/logo_ebay.png" alt="Logo" id="logo"> </a></li> -->
        <?php
        if(isset($_SESSION['pseudo'])){
				// PROFIL
          echo "<li><a class='link_menu' href=\"profil.php\" >" . $_SESSION['pseudo'] . "</a></li>";  
				
	   		// Liste des vendeurs et leurs profils
					echo "<li><a class='link_menu' href=\"profil_vendeurs.php\" >Vendeurs</a></li>";
				
				// Liste des objets mis en vente + leurs caractéristiques
          echo "<li><a class='link_menu' href=\"liste_objets.php\" >Objets</a></li>";

          if (isset($_SESSION['pseudo']) && isSeller($_SESSION['pseudo'])) {                
					// Vendre un objet en étant vendeur
            echo "<li><a class='link_menu' href=\"ajoutObjet.php\" >Vendre</a></li>";  
          }
        
          else{
					// Devenir un vendeur => formulaire
            echo "<li><a class='link_menu' href=\"inscription_vendeur.php\" >Devenir vendeur</a></li>";
            }

          if (isAdmin($_SESSION['pseudo'])) {
				  // Modifier les informations en étant admin
            echo "<li><a class='link_menu' href=\"adminHandling.php\" >Droits administrateur</a></li>";  
          }

				// deconnexion
          echo "<li><a class='link_menu' href=\"deconnexion.php\" >Se déconnecter</a></li>";
		}
			  
        else{
				// Si l'utilisateur n'est pas connecté
          echo "<li class><a class='link_menu' href=\"connexion.php \" >Se connecter</a></li>";
          echo "<li><a class='link_menu' href=\"inscription.php \" >S'inscrire</a></li>";
        }

        echo "<li><a class='link_menu' href=\"accueil.php\" >Accueil</a></li>";

        ?>
        <li><a class='link_menu' href=# class ="text_menu">Aide</a></li>
      
    </ul>
  </div>
</nav>

<nav>
<?php include("researchTool.php"); ?>
</nav>

<a class='logow' href=accueil.php> <img src="png/logo_ebay.png" alt="Logo" id="logo"> </a>