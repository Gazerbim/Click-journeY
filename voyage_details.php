<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Voyage</title>
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
    </div>

<?php 
    
    if (isset($_GET['id'])) {
        $voyageId = $_GET['id'];
    } else {
        echo "Voyage non trouvé";
        exit;
    }

        require_once 'requires/json_utilities.php';
    $voyage = recupererVoyageAvecId($voyageId);
    $etapes = recupererEtapesVoyage($voyageId);
    $nom = $voyage['nom'];

        $descriptionFile = "databases/voyages/{$voyageId}/description.txt";
    $dateFile = "databases/voyages/{$voyageId}/date.txt";
    
    $tarifFile = "databases/voyages/{$voyageId}/tarif.txt";
    $faqFile = "databases/voyages/{$voyageId}/faq.txt";
    $avisFile = "databases/voyages/{$voyageId}/avis.txt";

    
    $description = file_exists($descriptionFile) ? file_get_contents($descriptionFile) : "Description non disponible.";
    $date = file_exists($dateFile) ? file_get_contents($dateFile) : "Date non disponible.";
   
    $tarif = file_exists($tarifFile) ? file_get_contents($tarifFile) : "Tarif non disponible.";
    $faq = file_exists($faqFile) ? file_get_contents($faqFile) : "FAQ non disponible.";
    $avis = file_exists($avisFile) ? file_get_contents($avisFile) : "Avis non disponible.";
?>

    <div class="voyage_detail">
        <h1> <?php echo nl2br($nom); ?> </h1>
        <h3> <?php echo nl2br($description); ?> </h3>
        <h3> <?php echo nl2br($date); ?> </h3>
        <img src="databases/voyages/<?php echo $voyageId; ?>/img/profil.jpg" alt="image1" width="100%" height="20%">

        <h2> Programme du voyage ! </h2>
        <?php
        if (!empty($etapes)) {
            foreach ($etapes as $index => $etape) {
                $numEtape = $index + 1;
                echo "<h3>Étape $numEtape :</h3>";

                
                echo "<pre>" . file_get_contents("databases/voyages/{$voyageId}/etapes/jour{$numEtape}.txt") . "</pre>";

                                echo "<img src='databases/voyages/{$voyageId}/img/image_etape{$numEtape}.jpg' alt='image_etape{$numEtape}' width='100%' height='20%'>";
            }
        } else {
            echo "<p>Aucune étape disponible pour ce voyage.</p>";
        }
        ?>

        <div class="section">
            <h2>Tarif</h2>
            <p><?php echo nl2br($tarif); ?></p>
        </div>
        <div class="section">
            <h2>Avis</h2>
            <p><?php echo nl2br($avis); ?></p>
        </div>
        <div class="section">
            <h2>FAQ</h2>
            <p><?php echo nl2br($faq); ?></p>
        </div>
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
