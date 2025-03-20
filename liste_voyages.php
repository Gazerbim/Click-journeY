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
    <div class = "espaceur"></div>
    <div class="liste_voyages">
        <h2>Liste des voyages</h2>
        <div class="voyages-container">
            <?php
                if(isset($_GET['mot_clef'])){
                    $mots_clef = $_GET['mot_clef'];
                    $mots_clef = explode(" ", $mots_clef);
                }
                else{
                    $mots_clef = [""];
                }
                require_once 'requires/json_utilities.php';
                $voyages = recupererVoyages();
                foreach ($voyages as $value) {
                    $resultat = true;
                    $mots = avoirListeMotsVoyage($value['id']);
                    foreach($mots_clef as $mot_clef){
                        if (!(strpos(strtolower($mots), strtolower($mot_clef))!==false)){
                            $resultat = false;
                            break;
                        }
                    }
                    if($resultat){
                        echo "<div class='voyage'>";
                        echo "<img src='databases/voyages/" . $value['id'] . "/img/profil.jpg' alt='Voyage " . $value['id'] . "' width='100%' height='60%'>";
                        echo "<p>" . htmlspecialchars($value['nom']) . "</p>";
                        echo "<p>" . htmlspecialchars($value['description']) . "</p>";
                        echo "<p>" . htmlspecialchars($value['tarif']) . "€</p>";
                        echo "<a href='voyage_details.php?id=" . urlencode($value["id"]) . "'><button>Détails</button></a>";
                        echo "</div>";
                    }
                }
            ?>
        </div>
    </div>
    <div class="recherche">
        <h2>Recherche rapide</h2>
        <p>Vous cherchez un voyage en particulier ? Utilisez la recherche rapide pour trouver la destination qui vous convient :</p>
        <form action="liste_voyages.php" method="GET">
            <label for="mot_clef">Recherchez :</label>
            <input type="text" id="mot_clef" name="mot_clef" placeholder="Entrez un mot-clé...">
            <button type="submit">Rechercher</button>
        </form>
    </div>  
</body>
    
