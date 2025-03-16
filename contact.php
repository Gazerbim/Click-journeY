<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="contact-page">
<?php
    session_start();
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
                <?php
                    if (isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])) {
                        echo "<a href='deconnexion.php'><button>Déconnexion</button></a>";
                    } else {
                        echo "<a href='connexion.php'><button>Connexion</button></a>";
                    }
                ?>
                <a href="profil.php"><button>
                <?php
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
    <section class="recherche">
        <h2>Contactez-nous</h2>
        <p><strong>Remplissez le formulaire ci-dessous pour nous envoyer un message.</strong></p>
        <form>
            <label for="nom"><strong>Nom :</strong></label>
            <input type="text" id="nom" name="nom" required>
            
            <label for="email"><strong>Email :</strong></label>
            <input type="email" id="email" name="email" required>
            
            <label for="message"><strong>Message :</strong></label>
            <textarea id="message" name="message" rows="5" required></textarea>
            
            <button>Envoyer</button>
        </form>
    </section>
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
