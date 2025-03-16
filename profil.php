<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Profil utilisateur</title>
</head>
<body class="profil" >
<?php
    //session_start();
  ?>
    <div class="image_header">
        <nav>
            <a class="crous" href="https://www.crous-paris.fr/">
                <button><img src='images/Krous.png'></button>
            </a>
            <div class="nav-spacer"></div>
            <div class="nav-center">
                <h1 class="nav-titre">Rush&Krous</h1>
            </div>
            <div class="nav-liens">
                <a href="index.php"><button>Accueil</button></a>
                <a href="presentation.php"><button>Présentation</button></a>
                <a href="recherche.php"><button>Recherche</button></a>
                <a href="connexion.php"><button>Connexion</button></a>
                <a class="selected" href="profil.php"><button>
		<?php
        		session_start(); 
        		if (isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])) {
            			echo $_SESSION['prenom'];
        		} 
			else {
            			echo "Profil";
        		}
        	?>
		</button></a>
            </div>
        </nav>
        
    <div class="recherche">
        <h2>Mon Profil</h2>

        <?php

            require("requires/json_utilities.php");

            $nom = $_SESSION['nom'];
            $prenom = $_SESSION['prenom'];
            $email = $_SESSION['courriel'];
            $telephone = $_SESSION['tel'];
            $date_naissance = $_SESSION['naissance'];
        ?>

        <form action="/modifier_nom" method="post" class="formulaire-classique">
            <label for="nom"><strong>Nom :</strong></label>
            <div class="input-groupe">
            <input type="text" id="nom" name="nom" <?php echo "value='$nom'"; ?>>
            <button type="submit">Modifier</button>
            </div>
        </form>

        <form action="/modifier_prenom" method="post" class="formulaire-classique">
            <label for="prenom"><strong>Prénom :</strong></label>
            <div class="input-groupe">
            <input type="text" id="prenom" name="prenom" <?php echo "value='$prenom'"; ?>>
            <button type="submit">Modifier</button>
            </div>
        </form>

        <form action="/modifier_email" method="post" class="formulaire-classique">
            <label for="email"><strong>Email :</strong></label>
            <div class="input-groupe">
            <input type="email" id="email" name="email"<?php echo "value='$email'"; ?>>
            <button type="submit">Modifier</button>
            </div>
        </form>

        <form action="/modifier_telephone" method="post" class="formulaire-classique">
            <label for="telephone"><strong>Téléphone :</strong></label>
            <div class="input-groupe">
            <input type="tel" id="telephone" name="telephone" <?php echo "value='$telephone'"; ?>>
            <button type="submit">Modifier</button>
            </div>
        </form>

        <form action="/modifier_date_naissance" method="post" class="formulaire-classique">
            <label for="date_naissance"><strong>Date de naissance :</strong></label>
            <div class="input-groupe">
            <input type="date" id="date_naissance" name="date_naissance"<?php echo "value='$date_naissance'"; ?>>
            <button type="submit">Modifier</button>
            </div>
        </form>

        <form action="/modifier_mdp" method="post" class="formulaire-classique">
            <label for="mdp"><strong>Mot de passe :</strong></label>
            <input type="password" id="mdp" name="mdp">
            
            <label for="cmdp"><strong>Confirmer le mot de passe :</strong></label>
            <input type="password" id="cmdp" name="cmdp">
            
            <button type="submit">Modifier</button>
        </form>
    </div>

    <div>
        <form action="deconnexion.php" method="post" class="deconnexion">
            <a href="connexion.php"><button>Déconnexion</button></a>
        </form>
    </div>
    <div class="contact">
      <br>
      <p><strong>Rush&Krous</strong></p>
      
      <p>Av. du Parc, 95000 Cergy - t.lemenand@Rush&Krous.com - 06 52 60 77 34</p> 
      <a class="lien" href="conditions.php">Conditions d'utilisation</a><br>
      <a class="lien2" href="contact.php">Contact</a>
      <br>
      <a href="https://www.instagram.com/etudiantgouv/"><button><img class="instagram" src="https://cdn.iconscout.com/icon/free/png-256/free-instagram-1722380-1466166.png?f=webp" alt="instagram" ></button></a>
      <a href="https://www.facebook.com/etudiantgouv/"><button><img class="facebook" src="https://images.freeimages.com/fic/images/icons/2779/simple_icons/2048/facebook_2048_black.png" alt="Facebook" ></button></a>
      <a href="https://x.com/Cnous_LesCrous?mx=2"><button><img class="twitter" src="https://images.freeimages.com/image/large-previews/b2e/x-twitter-black-isolated-logo-5694253.png" alt="Twitter"> </button></a>
      
      
    </div>

</body>
</html>
