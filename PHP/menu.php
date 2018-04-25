<nav>
  <div>
    <ul>
        <?php
        if(isset($_SESSION['pseudo'])){
				  // PROFIL (menu déroulant)

          echo "<li class='sub-menu-parent' tab-index='0'>";
            echo"<a class='link_menu' href=\"profil.php\" >" . $_SESSION['pseudo'] . "</a>";
            echo "<ul class='sub-menu'>";
              echo "<li><a class='link_submenu' href='profil.php?opt=eval'>Vos evaluations</a></li>";

              if (isset($_SESSION['isSeller']) && $_SESSION['isSeller']) {
                echo "<li><a class='link_submenu' href='profil.php?opt=eval_r'>Evaluations reçues</a></li>";
                echo "<li><a class='link_submenu' href='profil.php?opt=obj'>Vos objets</a></li>";
              }

              echo "<li><a class='link_submenu' href='profil.php?opt=prop'>Vos propositions d'achat</a></li>";
              echo "<li><a class='link_submenu' href=\"deconnexion.php\" >Se déconnecter</a></li>";
            echo "</ul>";
          echo "</li>";
				
	   		  // Liste des vendeurs et leurs profils
					echo "<li><a class='link_menu' href=\"profil_vendeurs.php\" >Vendeurs</a></li>";
				
				  // Liste des objets mis en vente + leurs caractéristiques
          echo "<li><a class='link_menu' href=\"liste_objets.php\" >Objets</a></li>";
 
          if (isset($_SESSION['isSeller']) && $_SESSION['isSeller']) {          
					  // Vendre un objet en étant vendeur
            echo "<li><a class='link_menu' href=\"ajoutObjet.php\" >Vendre</a></li>";  
          }
        
          else{
					  // Devenir un vendeur => formulaire
            echo "<li><a class='link_menu' href=\"inscription_vendeur.php\" >Devenir vendeur</a></li>";
            }

          if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
				    // Modifier les informations en étant admin
            echo "<li><a class='link_menu' href=\"adminHandling.php\" >Droits administrateur</a></li>";  
          }

			  }
			  
        else{
        // Si l'utilisateur n'est pas connecté

          echo "<li class><a class='link_menu' href=\"connexion.php \" >Se connecter</a></li>";
          echo "<li><a class='link_menu' href=\"inscription.php \" >S'inscrire</a></li>";
        }

        ?>
        <li><a class='link_menu' href=# class ="text_menu">Aide</a></li>
      
    </ul>
  </div>
</nav>

<nav>
<?php include("researchTool.php"); ?>
</nav>

<!-- LOGO -->

<div class="dropdown">
  <span><a href=accueil.php> <img src="png/logo_ebay.png" alt="Logo" id="logo"> </a></span>
  <div class="dropdown-content">
    <p>Accueil</p>
  </div>
</div>