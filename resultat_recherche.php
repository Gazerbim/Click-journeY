<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Recherche</title>
</head>
<body class="rech" >
<?php
session_start();
require('requires/json_utilities.php');

if(isset($_GET['lieu']) || isset($_GET['date']) || isset($_GET['options'])) {
// Get search parameters
$lieu = isset($_GET['lieu']) ? trim($_GET['lieu']) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';
$options = isset($_GET['options']) ? $_GET['options'] : array();

// Load voyages data
$voyages = lireFichierJson('databases/voyages.json');

if($voyages) {
// Filter voyages based on search criteria
$filteredVoyages = array();

foreach($voyages as $voyage) {
    $match = true;

    // Filter by lieu if provided (check in nom and etapes/ville)
    if(!empty($lieu)) {
        $lieuMatch = false;

        // Check in voyage name
        if(stripos($voyage['nom'], $lieu) !== false) {
            $lieuMatch = true;
        }

        // Check in etapes/ville
        if(!$lieuMatch && isset($voyage['etapes'])) {
            foreach($voyage['etapes'] as $etape) {
                if(stripos($etape['ville'], $lieu) !== false) {
                    $lieuMatch = true;
                    break;
                }
            }
        }

        if(!$lieuMatch) {
            $match = false;
        }
    }

    // Filter by date if provided (check if it falls between debut and fin)
    if(!empty($date) && isset($voyage['debut']) && isset($voyage['fin'])) {
        $voyageDebut = strtotime(str_replace('/', '-', $voyage['debut']));
        $voyageFin = strtotime(str_replace('/', '-', $voyage['fin']));
        $searchDate = strtotime($date);

        if($searchDate < $voyageDebut || $searchDate > $voyageFin) {
            $match = false;
        }
    }

    // Filter by options
    // Option c: With Accommodation - check if logement exists in etapes
    if(in_array('c', $options) && isset($voyage['etapes'])) {
        $hasAccommodation = false;
        foreach($voyage['etapes'] as $etape) {
            if(!empty($etape['logement'])) {
                $hasAccommodation = true;
                break;
            }
        }
        if(!$hasAccommodation) {
            $match = false;
        }
    }

    // Option d: With theme - check if description contains theme-related words
    if(in_array('d', $options) && (!isset($voyage['description']) || empty($voyage['description']))) {
        $match = false;
    }

    if($match) {
        $filteredVoyages[] = $voyage;
    }
}

// Sort by price if selected (since there's no rating field, we'll use price)
if(in_array('a', $options)) {
    // Lowest price first (as better value)
    usort($filteredVoyages, function($a, $b) {
        if ($a['tarif'] == $b['tarif']) return 0;
        return ($a['tarif'] < $b['tarif']) ? -1 : 1;
    });
} elseif(in_array('b', $options)) {
    // Highest price first (as worse value)
    usort($filteredVoyages, function($a, $b) {
        if ($a['tarif'] == $b['tarif']) return 0;
        return ($a['tarif'] > $b['tarif']) ? -1 : 1;
    });
}
?>
<div class="image_header">
    <nav>
        <a class="crous" href="https://www.crous-paris.fr/">
            <button><img src='images/Krous.png' alt="Krous"></button>
        </a>
        <div class="nav-spacer"></div>
        <div class="nav-center">
            <h1 class="nav-titre">Rush&Krous</h1>
        </div>
        <div class="nav-liens">
            <a href="index.php"><button>Accueil</button></a>
            <a href="presentation.php"><button>Présentation</button></a>
            <a class="selected" href="recherche.php"><button>Recherche</button></a>
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

    <div class="recherche">
        <h2>Recherche de voyages</h2>
        <form action="recherche.php" method="GET">
            <label for="lieu"><strong>Crous à rechercher :</strong></label>
            <input type="text" id="lieu" name="lieu" placeholder="Entrez un krous que vous voulez visiter...">
            <br><br>
            <label for="date"><strong>Quand voulez-vous partir ?</strong></label>
            <input type="date" id="date" name="date">
            <br><br>
            <label for="options"><strong>Options :</strong></label>
            <div class="checkbox-groupe">
                <input type="checkbox" id="options" name="options[]" value="a">Meilleur Note
                <input type="checkbox" id="options" name="options[]" value="b">Pire Note
                <input type="checkbox" id="options" name="options[]" value="c">Avec Hebergement
                <input type="checkbox" id="options" name="options[]" value="d">A Thème
            </div>
            <br><br>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <div class="liste_voyages">
        <h2>Résultats de la recherche</h2>
        <?php
        // Display results
        if(count($filteredVoyages) > 0) {
            echo "<div class='resultats'>";
            foreach($filteredVoyages as $voyage) {
                echo "<div class='voyage-card'>";
                echo "<h3>" . htmlspecialchars($voyage['nom']) . "</h3>";
                echo "<p>Description: " . htmlspecialchars($voyage['description']) . "</p>";
                echo "<p>Prix: " . htmlspecialchars($voyage['tarif']) . "€</p>";
                echo "<p>Période: " . htmlspecialchars($voyage['debut']) . " au " . htmlspecialchars($voyage['fin']) . "</p>";

                if(isset($voyage['etapes']) && !empty($voyage['etapes'])) {
                    echo "<p>Étapes :</p><ul>";
                    foreach($voyage['etapes'] as $etape) {
                        echo "<li>" . htmlspecialchars($etape['ville']) . " - ";
                        echo "du " . htmlspecialchars($etape['debut']) . " au " . htmlspecialchars($etape['fin']);
                        if(!empty($etape['logement'])) {
                            echo "<br>Logement: " . htmlspecialchars($etape['logement']);
                        }
                        echo "</li>";
                    }
                    echo "</ul>";
                }

                echo "<p><a href='details_voyage.php?id=" . htmlspecialchars($voyage['id']) . "' class='btn-details'>Voir les détails</a></p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Aucun voyage ne correspond à votre recherche.</p>";
        }
        } else {
            echo "<p>Erreur lors du chargement des données de voyages.</p>";
        }
        } else {
            echo "<p>Veuillez effectuer une recherche pour voir les résultats.</p>";
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
