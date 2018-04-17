<nav id="menu">
    <div class="element_menu">
        <ul>
            <li><a href=accueil.php> <img src="png/ebay.png" alt="Logo" id="logo"> </a></li>
            <?php
              if(isset($_SESSION['pseudo'])){
                //echo "<li> Bonjour " . $_SESSION['pseudo'] . "</li>";
                echo "<li><a href=# class = \"text_menu\">" . $_SESSION['pseudo'] . "</a></li>";
                echo "<li><a href=\"profil_vendeurs.php\" class = \"text_menu\">Vendeurs</a></li>";
                echo "<li><a href=\"objets.php\" class = \"text_menu\">Objets</a></li>";

                // if (isset($_SESSION['pseudo']) && isSeller($_SESSION['pseudo'])) {                
                //     echo "<li><a href=\"ajoutObjet.php\" class = \"text_menu\">Vendre</a></li>";  
                // }
        
                // else
                // {
                //     echo "<li><a href=\"inscription_vendeur.php\" class = \"text_menu\">Devenir vendeur</a></li>";
                // }
                echo "<li><a href=\"inscription_vendeur.php\" class = \"text_menu\">Devenir vendeur</a></li>";
                echo "<li><a href=\"deconnexion.php\" class = \"text_menu\">Se déconnecter</a></li>";

              }

              else{
                echo "<li class><a href=\"connexion.php \" class = \"text_menu\">Se connecter</a></li>";
                echo "<li><a href=\"inscription.php \" class = \"text_menu\">S'inscrire</a></li>";
              }
            ?>
            <li><a href=# class ="text_menu">Aide</a></li>
        </ul>
        <form action="recherche.php" method="post" id='search'>
          <p>
            <input type="text" name="recherche" />
            <select name="categorie">
              <?php
                $categories = $bdd->query('SELECT Titre FROM Categorie');
                while($donne = $categories->fetch()){
                  echo "<option value= \"".$donne['Titre']. "\">" .$donne['Titre']. "</option>";
                }
                $categories->closeCursor();
               ?>
            </select>
            <input type="submit" value="🔎" />
          </p>
        </form>
    </div>
</nav>
