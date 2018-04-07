<?php 
	session_start();  // On démarre la session
?> 

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>Connexion</title>
   </head>

   <body>
		<h2>Connexion !</h2>

		<p>
			Ceci est la page de connexion du site Ebay.<br />
			Veuillez taper votre pseudo et votre mot de passe:
		</p>
		
		
		<form action="accueil.php" method="post">
			<p>
				Pseudo:<br>
				<input type="text" name="pseudo" /> <br />
				Mot de passe:<br>
				<input type="password" name="motdepasse" /> <br />
				<input type="submit" value="Se connecter" />
			</p>

		</form>

		<p> <a href="main.php"> Retour </a></p>

   </body>
</html>
