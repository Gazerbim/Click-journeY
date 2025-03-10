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
        <h2>Connexion</h2>
 
        <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo "<p style='color: #e30613;'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
        ?>

        <form action="connexionP.php" method="POST">
            <label for="email"><strong>Courriel :</strong></label>
            <input type="email" id="email" name="email" placeholder="Entrez une adresse courriel valide..." required="true">
            <br></br>
            <label for="mdp"><strong>Mot de passe :</strong></label>
            <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe..." required="true">
            <br></br>
            <button type="submit">Connexion</button>
        </form>
        <p></p>
        <a href="inscription.html">Vous n'avez pas de compte ?</a>
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


