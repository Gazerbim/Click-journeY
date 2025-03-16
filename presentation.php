<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Présentation</title>
</head>
<body>
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
                <a class="selected" href="presentation.php"><button>Présentation</button></a>
                <a href="recherche.php"><button>Recherche</button></a>
                <a href="connexion.php"><button>Connexion</button></a>
                <a href="profil.php"><button>
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
    <div class="presentation">
        <h1>Bienvenue chez Rush&Krous : L'agence de voyage des étudiants !</h1>

        <h3>Chez le Crous, nous avons toujours eu à cœur d'accompagner les étudiants dans leur quotidien. 
            Aujourd'hui, nous allons encore plus loin en lançant Rush&Krous, une agence de voyage conçue spécialement pour les étudiants.</h3>
        
        <h2>Qu'est-ce que Rush&Krous ?</h2>
        
        <p>Rush&Krous est une initiative unique qui permet aux étudiants de découvrir la richesse des régions françaises tout en bénéficiant des infrastructures du Krous. 
            Grâce à nos voyages, explorez différentes villes étudiantes, goûtez à des spécialités locales dans les restaurants universitaires et rencontrez des étudiants de toute la France.</p>

        <img src="images/lesKrous.jpg" alt="Les Krous" width="100%" height="20%">
        
        <h2>Nos offres de voyage</h2>

        <h4>Nous proposons des séjours accessibles et adaptés aux besoins des étudiants :</h4>


            <h1>Voyages culturels</h1>
            <h4 class="center">Partez à la découverte du patrimoine historique et artistique des villes où le Krous est présent.</h4>
            <div class="image">
                <img src="images/Paris.jpg" alt="Paris">
                <img src="images/Marseille.jpg" alt="Marseille">
                <img src="images/Bordeaux.jpg" alt="Bordeaux">
                <img src="images/Lille.jpg" alt="Lille">
            </div>
            <h1>Séjours gastronomiques</h1>
            <h4 class="center">Profitez des spécialités culinaires locales dans nos restaurants universitaires.</h4>
            <div class="image1">
                <img src="images/Resto1.jpg" alt="Restaurant Krous">
                <img src="images/Resto3.jpg" alt="Restaurant Krous">
                <img src="images/Resto2.jpg" alt="Restaurant Krous">
            </div>
            <h1>Échanges étudiants</h1>
            <h4 class="center">Rencontrez des étudiants d'autres régions et partagez vos expériences.</h4>
            <img src="images/Etudiants.jpg" alt="Étudiants" width="100%" height="20%">
            <h1>Éco-découvertes</h1>
            <h4 class="center">Voyagez de manière responsable et explorez des initiatives durables mises en place par les Krous.</h4>
            <table>
                <tr>
                    <img src="images/autocar.jpg" alt="Autocar" width="50%" height="20%">
                    <img src="images/tgv.jpg" alt="TGV" width="50%" height="20%">
                </tr>
                
            </table>
        
        </ul>
    </div>
    
    <div class="recherche">
        <h2>Recherche rapide</h2>
        <p>Vous cherchez un voyage en particulier ? Utilisez la recherche rapide pour trouver la destination qui vous convient :</p>
        <form action="#" method="GET">
            <label for="search">Recherchez :</label>
            <input type="text" id="search" name="search" placeholder="Entrez un mot-clé...">
            <button type="submit">Rechercher</button>
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
