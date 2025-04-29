<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
	<link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <title>Rush&Krous - Inscription</title>
</head>
<body class="inscription">
<?php
    session_start();
    require('requires/header.php');
    afficher_header('connexion');
  ?>
    <div class="recherche light-mode">
        <h2>Inscription</h2>
	
	<?php
	    require_once 'requires/json_utilities.php';
	    if (isset($_SESSION['error'])) {
                echo "<p style='color: #e30613;'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
	    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
    		$mdp = isset($_POST['mdp']) ? trim($_POST['mdp']) : '';
		$telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
		$nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
		$prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
		$date_naissance = isset($_POST['date_naissance']) ? trim($_POST['date_naissance']) : '';
		$genre = isset($_POST['options']) ? trim($_POST['options']) : '';
		$role = "user";
		
		//if (empty($email) || empty($mdp) || empty($telephone) || empty($nom) || empty($prenom) || empty($date_naissance) || empty($genre)) {
        		//$_SESSION['error'] = "Veuillez remplir tous les champs";
        		//header('Location: inscription.php');
       			//exit;
	        //}

		if (!verifCourriel($email)){
		    $_SESSION['error'] = "Cette adresse Email existe dèjà sur Rush&Krous";
		    header('location: inscription.php');
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
		//$_SESSION['mdp'] = $mdp;
        	header('Location: index.php'); 
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
            <input type="date" id="date_naissance" max="2025-01-01" name="date_naissance" required="true" title="Veuillez entrer une date antérieure à 2025">
            <br></br>
            <label for="options"><strong>Genre :</strong></label>
            <div class="radio-groupe">
                <input type="radio" name="options" value="homme" required="true">Homme
                <input type="radio" name="options" value="femme" required="true">Femme
                <input type="radio" name="options" value="autre" required="true">Autre
	    </div>
            <br></br>
            <label for="telephone"><strong>Téléphone :</strong></label>
            <input type="tel" pattern="^0[1-9][0-9]{8}$" id="telephone" name="telephone" placeholder="Entrez votre numéro..." required="true" title="entrer un numéro de téléphone valide au format 06XXXXXXXX">
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
    <?php
        require('requires/footer.php');
    ?>
    <script src="script.js"></script>
</body>
</html>
