<form action="recherche.php" method="post" id='search'>
  <p>
    <input type="text" placeholder="Rechercher.." name="recherche" />
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
