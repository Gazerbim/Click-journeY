<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Connexion</title>
</head>
<body class="connexion" >
   <?php
    session_start();
    if (isset($_SESSION['id'])) {
        $_SESSION['error'] = "Vous êtes déjà connecté";
        header('Location: profil.php');
        exit;
    }
    require('requires/header.php');
    afficher_header('connexion');
  ?>
    <div class="recherche">
        <h2>Connexion</h2>
 
        <?php
            
	    require_once 'requires/json_utilities.php';
            if (isset($_SESSION['error'])) {
                echo "<p style='color: #e30613;'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
	   
 

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
    		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
    		$mdp = isset($_POST['mdp']) ? trim($_POST['mdp']) : '';

   
    		if (empty($email) || empty($mdp)) {
        		$_SESSION['error'] = "Veuillez remplir tous les champs";
        		header('Location: connexion.php');
        		exit;
   		}

    
    		$utilisateurs = lireFichierJson("./databases/users.json");

    		if (!$utilisateurs) {
        		$_SESSION['error'] = "Erreur de lecture des utilisateurs";
        		header('Location: connexion.php');
        		exit;
    		}

    		$utilisateur_trouve = checkIdentifiants($email, $mdp);
    
    		if ($utilisateur_trouve) {
			$id = getId($email, $mdp);
            $utilisateur = recupereInfosUtilisateur($id);
			$nom = $utilisateur['nom'];
            $prenom = $utilisateur['prenom'];
            $tel = $utilisateur['tel'];
            $naissance = $utilisateur['naissance'];
            $role = $utilisateur['role'];
            $genre = $utilisateur['genre'];
			
			$_SESSION['id'] = $id;
			//$_SESSION['mdp'] = $mdp;
			$_SESSION['courriel'] = $email;
			$_SESSION['nom'] = $nom;
			$_SESSION['prenom'] = $prenom;
			$_SESSION['tel'] = $tel;
			$_SESSION['naissance'] = $naissance;
			$_SESSION['role'] = $role;
			$_SESSION['genre'] = $genre;
        		header('Location: index.php'); 
        		exit;
    		} else {
        		$_SESSION['error'] = "Email ou mot de passe incorrect";
        		header('Location: connexion.php');         
			exit;
    		}
	    }
        ?>

        <form action="connexion.php" method="POST">
            <label for="email"><strong>Courriel :</strong></label>
            <input type="email" id="email" name="email" placeholder="Entrez une adresse courriel valide..." required="true">
            <br></br>
            <label for="mdp"><strong>Mot de passe :</strong></label>
            <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe..." required="true">
            <br></br>
            <button type="submit">Connexion</button>
        </form>
        <p></p>
        <a href="inscription.php">Vous n'avez pas de compte ?</a>
    </div>
    <?php
        require('requires/footer.php');
    ?>
</body>
</html>


