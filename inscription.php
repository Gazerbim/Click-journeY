<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
	<link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <title>Rush&Krous - Inscription</title>
</head>
<body class="inscription">
  
    <nav>
        <a class="crous" href="https://www.crous-paris.fr/">
            <button><img src='images/Krous.png'></button>
        </a>
        <div class="nav-spacer"></div>
            <div class="nav-center">
                <h1 class="nav-titre">Rush&Krous</h1>
            </div>
        <div class="nav-liens">
            <a href="index.html"><button>Accueil</button></a>
            <a href="presentation.html"><button>Présentation</button></a>
            <a href="recherche.html"><button>Recherche</button></a>
            <a class="selected" href="connexion.php"><button>Connexion</button></a>
            <a href="profil.html"><button>Profil</button></a>
        </div>
    </nav>
    
    <div class="recherche">
        <h2>Inscription</h2>
	
	<?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo "<p style='color: #e30613;'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
	    require_once 'requires/json_utilities.php';
	    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
    		$mdp = isset($_POST['mdp']) ? trim($_POST['mdp']) : '';
		$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
		$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
		$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
		$date_naissance = isset($_POST['date_naissance']) ? trim($_POST['date_naissance']) : '';
		$genre = isset($_POST['options']) ? trim($_POST['options']) : '';
		$role = "user";
		
		if (empty($email) || empty($mdp) || empty($telephone) || empty($nom) || empty($prenom) || empty($date_naissance) || empty($genre)) {
        		$_SESSION['error'] = "Veuillez remplir tous les champs";
        		header('Location: inscription.php');
       			exit;
	        }
	
            $user_id = ajouterUtilisateur($nom, $prenom, $mdp, $role, $date_naissance, $genre, $telephone, $email);
	    $utilisateur_trouve = checkUtilisateur($email, $mdp, $prenom, $nom, $telephone, $date_naissance, $genre, $role);
		
	    if ($utilisateur_trouve) {
		$_SESSION['id'] = $user_id;
        	$_SESSION['nom'] = $nom;
        	$_SESSION['prenom'] = $prenom;
        	$_SESSION['courriel'] = $email;
        	$_SESSION['role'] = $role;
		$_SESSION['tel'] = $telephone;
		$_SESSION['genre'] = $genre;
		$_SESSION['naissance'] = $date_naissance;
		$_SESSION['mdp'] = $mdp;
        	header('Location: index.html'); 
        	exit;
    	    } else {
        	$_SESSION['error'] = "Votre compte n'a pas bien été créé, veuillez recommencer";
        	header('Location: inscription.php');         
	        exit;
    	    }

            }

		
        ?>

        <form action="inscription.php" method="POST">
            <label for="nom"><strong>Nom :</strong></label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom..." required="true">
            <br></br>
            <label for="prenom"><strong>Prénom :</strong></label>
            <input type="text" id="prenom" name="prenom" placeholder="Votre prénom..." required="true">
            <br></br>
            <label for="lieu"><strong>Date de naissance :</strong></label>
            <input type="date" id="date_naissance" name="date_naissance" required="true">
            <br></br>
            <label for="options"><strong>Genre :</strong></label>
            <div class="radio-groupe">
                <input type="radio" name="options" value="homme" required="true">Homme
                <input type="radio" name="options" value="femme" required="true">Femme
                <input type="radio" name="options" value="autre" required="true">Autre
	    </div>
            <br></br>
            <label for="telephone"><strong>Téléphone :</strong></label>
            <input type="tel" id="telephone" name="telephone" placeholder="Entrez votre numéro..." required="true">
            <br></br>
            <label for="email"><strong>Adresse courriel :</strong></label>
            <input type="email" id="email" name="email" placeholder="Entrez une adresse courriel valide..." required="true">
            <br></br>
            <label for="mdp"><strong>Mot de passe :</strong></label>
            <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe..." required="true">
            <br></br>
            <button type="submit">Inscription</button>
        </form>
        <p></p>
        <a href="connexion.php">Vous avez déjà un compte ?</a>
    </div>
    <div class="contact">
      <br>
      <p><strong>Rush&Krous</strong></p>
      
      <p>Av. du Parc, 95000 Cergy - t.lemenand@Rush&Krous.com - 06 52 60 77 34</p> 
      <a class="lien" href="conditions.html">Conditions d'utilisation</a><br>
      <a class="lien2" href="contact.html">Contact</a>
      <br>
      <a href="https://www.instagram.com/etudiantgouv/"><button><img class="instagram" src="https://cdn.iconscout.com/icon/free/png-256/free-instagram-1722380-1466166.png?f=webp" alt="instagram" ></button></a>
      <a href="https://www.facebook.com/etudiantgouv/"><button><img class="facebook" src="https://images.freeimages.com/fic/images/icons/2779/simple_icons/2048/facebook_2048_black.png" alt="Facebook" ></button></a>
      <a href="https://x.com/Cnous_LesCrous?mx=2"><button><img class="twitter" src="https://images.freeimages.com/image/large-previews/b2e/x-twitter-black-isolated-logo-5694253.png" alt="Twitter"> </button></a>
      
      
    </div>
</body>
</html>
