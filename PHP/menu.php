<nav id="menu">
    <div class="element_menu">
        <ul>
            <li><a href=accueil.php> <img src="png/ebay.png" alt="Logo" id="logo"> </a></li>
            <?php
              if(isset($_SESSION['pseudo'])){
                echo "<li> Bonjour " . $_SESSION['pseudo'] . "</li>";
                echo "<li><a href=# class = \"text_menu\">Profil</a></li>";
                echo "<li><a href=# class = \"text_menu\">Vendre</a></li>";
                echo "<li><a href=\"deconnexion.php\" class = \"text_menu\">Se deconnecter</a></li>";

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
