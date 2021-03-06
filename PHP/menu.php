<nav>
  <div>
    <ul>
        <?php
        if(isset($_SESSION['pseudo'])){
				  // PROFIL (menu déroulant)

          echo "<li class='sub-menu-parent' tab-index='0'>";
            echo"<a class='link_menu' href=\"profil.php\" >" . $_SESSION['pseudo'] . "</a>";
            echo "<ul class='sub-menu'>";
              echo "<li><a class='link_submenu' href='profil.php?opt=eval'>→ Vos evaluations</a></li>";

              if (isset($_SESSION['isSeller']) && $_SESSION['isSeller']) {
                echo "<li><a class='link_submenu' href='profil.php?opt=eval_r'>→Evaluations reçues</a></li>";
                echo "<li><a class='link_submenu' href='profil.php?opt=obj'>→Vos objets</a></li>";
                echo "<li><a class='link_submenu' href='gestion_propositions.php?opt=obj'>
                  →Propositions d'achat reçues</a></li>";
              }

              echo "<li><a class='link_submenu' href='profil.php?opt=prop'>→Vos propositions d'achat</a></li>";
              // Evaluer des vendeurs
              echo "<li><a class='link_submenu' href='profil.php?opt=eval_v'>→Evaluer des vendeurs</a></li>";
          
              //Modifier infos personelles
              echo "<li><a class='link_submenu' href='modProfile.php'>→Modifier informations personnelles</a></li>";

              echo "<li><a class='link_submenu' href=\"deconnexion.php\" >→Se déconnecter</a></li>";
            echo "</ul>";
          echo "</li>";

          if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
            // Modifier les informations en étant admin
            echo "<li class='sub-menu-parent' tab-index='0'>";
              echo "<a class='link_menu' href=\"adminHandling.php\" >Droits administrateur</a>";
              echo "<ul class='sub-menu'>";
                echo "<li><a class='link_submenu' href=addAdmin.php \" class = \"text_menu\">→  Ajouter un administrateur</a></li><br />";
                echo "<li><a class='link_submenu' href=suppressAccount.php \" class = \"text_menu\">→ Supprimer un compte</a></li><br />";
                echo "<li><a class='link_submenu' href=handleCategories.php \" class = \"text_menu\">→ Gestion des catégories</a></li><br />";
              echo "</ul>";
            echo "</li>";
          }
				
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

          echo "<li class='sub-menu-parent' tab-index='0'>";
            echo"<a class='link_menu' href=# >" . 'Requêtes' . "</a>";
            echo "<ul class='sub-menu'>";
              echo "<li><a class='link_submenu' href='requetes.php?r=1'>R1</a></li>";
              echo "<li><a class='link_submenu' href='requetes.php?r=2'>R2</a></li>";
              echo "<li><a class='link_submenu' href='requetes.php?r=3'>R3</a></li>";
              echo "<li><a class='link_submenu' href='requetes.php?r=4'>R4</a></li>";
              echo "<li><a class='link_submenu' href='requetes.php?r=5'>R5</a></li>";
              echo "<li><a class='link_submenu' href='requetes.php?r=6'>R6</a></li>";
            echo "</ul>";
          echo "</li>";

			  }
			  
        else{
        // Si l'utilisateur n'est pas connecté

          echo "<li class><a class='link_menu' href=\"connexion.php \" >Se connecter</a></li>";
          echo "<li><a class='link_menu' href=\"inscription.php \" >S'inscrire</a></li>";
        }

        ?>
        <!-- <li><a class='link_menu' href=# class ="text_menu">Aide</a></li> -->
      
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