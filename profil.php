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
                <a class="selected" href="profil.php"><button>
                <?php
                    if (isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])) {
                        echo $_SESSION['prenom'];
                        } 
                    else {
                        echo "Profil";
			$_SESSION['error'] = "Vous devez vous connecter a votre compte";
                        header('Location: connexion.php');
                    }
                ?>
		</button></a>
            </div>
        </nav>
        
    <div class="recherche">
        <h2>Mon Profil</h2>

        <?php
            
            require("requires/json_utilities.php");
            if (isset($_SESSION['error'])) {
                echo "<p style='color: #e30613;'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "<p style='color: #00a000;'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }
            if($_SESSION['role'] == "adm") {
                echo "<a href='admin.php'><button>VOIR UTILISATEURS</button></a>";
            }
            $nom = $_SESSION['nom'];
            $prenom = $_SESSION['prenom'];
            $email = $_SESSION['courriel'];
            $telephone = $_SESSION['tel'];
            $date_naissance = $_SESSION['naissance'];
        ?>

        <form action="modifier_profil.php" method="post" class="formulaire-classique">
            <label for="nom"><strong>Nom :</strong></label>
            <div class="input-groupe">
            <input type="text" id="nom" name="nom" <?php echo "value='$nom'"; ?>>
            <button type="submit" name="action" value="modifier_nom">Modifier</button>
            </div>
        </form>

        <form action="modifier_profil.php" method="post" class="formulaire-classique">
            <label for="prenom"><strong>Prénom :</strong></label>
            <div class="input-groupe">
            <input type="text" id="prenom" name="prenom" <?php echo "value='$prenom'"; ?>>
            <button type="submit" name="action" value="modifier_prenom">Modifier</button>
            </div>
        </form>

        <form action="modifier_profil.php" method="post" class="formulaire-classique">
            <label for="email"><strong>Email :</strong></label>
            <div class="input-groupe">
            <input type="email" id="email" name="email"<?php echo "value='$email'"; ?>>
            <button type="submit" name="action" value="modifier_email">Modifier</button>
            </div>
        </form>

        <form action="modifier_profil.php" method="post" class="formulaire-classique">
            <label for="telephone"><strong>Téléphone :</strong></label>
            <div class="input-groupe">
            <input type="tel" id="telephone" name="telephone" <?php echo "value='$telephone'"; ?>>
            <button type="submit" name="action" value="modifier_telephone">Modifier</button>
            </div>
        </form>

        <form action="modifier_profil.php" method="post" class="formulaire-classique">
            <label for="date_naissance"><strong>Date de naissance :</strong></label>
            <div class="input-groupe">
            <input type="date" id="date_naissance" name="date_naissance"<?php echo "value='$date_naissance'"; ?>>
            <button type="submit" name="action" value="modifier_date_naissance">Modifier</button>
            </div>
        </form>

        <form action="modifier_profil.php" method="post" class="formulaire-classique">
            <label for="mdp"><strong>Mot de passe :</strong></label>
            <input type="password" id="mdp" name="mdp">
            
            <label for="cmdp"><strong>Confirmer le mot de passe :</strong></label>
            <input type="password" id="cmdp" name="cmdp">
            
            <button type="submit" name="action" value="modifier_mdp">Modifier</button>
        </form>
    </div>

    <div>
        <form action="deconnexion.php" method="post" class="deconnexion">
            <a href="connexion.php"><button>Déconnexion</button></a>
        </form>
    </div>
    
    <div class="mes-voyages-container">
        <h2>Mes Voyages Réservés</h2>
        <?php
            $id = $_SESSION['id'];
            // Charger les voyages réservés par l'utilisateur
            $voyages_utilisateur = recupererVoyagesUtilisateur($id);
            
            // Charger les voyages disponibles
            $voyages_disponibles = recupererVoyages();

            // Associer les voyages réservés à leurs informations
            $voyages_reserves = [];
            foreach ($voyages_utilisateur as $reservation) {
                foreach ($voyages_disponibles as $voyage) {
                    if ($voyage['id'] == $reservation['id']) {
                        $voyage['date_reservation'] = $reservation['date'];
                        $voyage['transaction'] = $reservation['transaction'];
                        $voyages_reserves[] = $voyage;
                        break;
                    }
                }
            }

            if (!empty($voyages_reserves)) {
                echo "<div class='mes-voyages-liste'>";
                foreach ($voyages_reserves as $voyage) {
                    echo "<div class='mes-voyages-card'>";
                    echo "<h3 class='mes-voyages-titre'>" . htmlspecialchars($voyage['nom']) . "</h3>";
                    echo "<img src='databases/voyages/" . $voyage['id'] . "/img/profil.jpg' alt='Voyage " . $voyage['id'] . "' width='100%' height='25%'>";
                    echo "<p class='mes-voyages-description'>" . htmlspecialchars($voyage['description']) . "</p>";
                    echo "<p><strong>Réservé le :</strong> " . htmlspecialchars($voyage['date_reservation']) . "</p>";
                    echo "<p><strong>Période :</strong> " . htmlspecialchars($voyage['debut']) . " - " . htmlspecialchars($voyage['fin']) . "</p>";
                    echo "<p><strong>Tarif :</strong> " . htmlspecialchars($voyage['tarif']) . "€</p>";
                    echo "<p><strong>Transaction :</strong> " . htmlspecialchars($voyage['transaction']) . "</p>";
                    echo "<a href='voyage_details.php?id=" . htmlspecialchars($voyage['id']) . "' class='mes-voyages-btn'>Détails</a>";
                    echo "<br>";
                    echo "<a href='annuler_voyage.php?id=" . htmlspecialchars($voyage['id']) . "' class='mes-voyages-btn2'>Annuler réservation</a>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p class='mes-voyages-message'>Aucun voyage réservé.</p>";
            }
        ?>
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
