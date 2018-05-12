<select name="categorie">
        <?php
        $categories = $bdd->query('SELECT Titre FROM Categorie');
        while($donne = $categories->fetch()){
          echo "<option value= \"".$donne['Titre']. "\">" .$donne['Titre']. "</option>";
        }
        $categories->closeCursor();
        ?>
</select>