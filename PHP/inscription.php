<?php 
	session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>Inscription</title>
   </head>

   <body>
		<h2>Inscription !</h2>

		<p>
			Ceci est la page d'inscription du site Ebay.<br />
			Veuillez compléter le formulaire d'inscription:
		</p>
		
		
		<form action="accueil.php" method="post">
			<p>
				Pseudo: <br /> 
                <input type="text" name="pseudo" /> <br /> 
				Mot de passe: <br />
                <input type="password" name="motdepasse" /> <br />
                Age: <br/ >
                <input type="number" name="age" min="1" max="100" /> <br />
                Adresse E-mail: <br />
                <input type="email" name="email" /> <br />
				<input type="submit" value="S'inscrire" />
			</p>

		</form>

        <p> <a href="main.php"> Retour </a></p>

   </body>
</html>
