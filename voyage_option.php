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
        include("requires/header.php");
        afficher_header("profil");
        require('requires/json_utilities.php');
        if (!isset($_SESSION['id']) && empty($_SESSION['id'])) {
            header('Location: connexion.php');
            exit;
        }
        
        if (isset($_GET['id'])) {
            $voyageId = $_GET['id'];
        } else {
            echo "Voyage non trouvé";
            exit;
        }
        
        $user_id = $_SESSION['id'];
        $voyages = recupererVoyagesUtilisateur($user_id);

        if (is_string($voyages)) {
            $voyages = json_decode($voyages, true);
        }

        if (!is_array($voyages)) {
            echo "Aucun voyage trouvé.";
            exit;
        }

        // Récupérer les informations du voyage avec l'ID
        $voyage = recupererVoyageAvecId($voyageId);
        $etapes = recupererEtapesVoyage($voyageId);
        $nom = $voyage['nom'];

        $descriptionFile = "databases/voyages/{$voyageId}/description.txt";
        $dateFile = "databases/voyages/{$voyageId}/date.txt";

        $description = file_exists($descriptionFile) ? file_get_contents($descriptionFile) : "Description non disponible.";
        $date = file_exists($dateFile) ? file_get_contents($dateFile) : "Date non disponible.";

	$optionsFile = "databases/voyages/{$voyageId}/options.json";
        if (file_exists($optionsFile)) {
            $optionsPrices = json_decode(file_get_contents($optionsFile), true);
        } else {
            $optionsPrices = [];
        }
    ?>

    <div class="voyage_detail">
        <h1><?php echo nl2br($nom); ?></h1>
        <h3><?php echo nl2br($description); ?></h3>
        <h3><?php echo nl2br($date); ?></h3>
        <img src="databases/voyages/<?php echo $voyageId; ?>/img/profil.jpg" alt="image1" width="100%" height="20%">
        <div class="recherche">
        <a href="voyage_details.php?id=<?php echo $voyageId ?>"><button>Aller au voyage</button></a>
        </div>
    </div>
    <form action="paiement_options.php?id=<?php echo htmlspecialchars($voyageId); ?>" method="POST" class="recherche">
    <h2>Options du voyage</h2>
    <?php

        foreach ($voyages as $v1) {
            if ($v1["id"] == $voyageId) {
                if (isset($v1['options'])) {
                    echo "<table class='options-table'>";
                    foreach ($v1['options'] as $optionName => $optionValue) {
                        $price = $optionsPrices[$optionName];
                        echo "<tr class='option-row'>";
                        echo "<td><label class='option-label'>" . htmlspecialchars(str_replace('_', ' ', $optionName)) . "</label></td>";
                        echo "<td><input type='checkbox' name='Options[]' value='" . htmlspecialchars($optionName) . "' class='option-checkbox'";
                        if ($optionValue === "true" || $optionValue === true) {
                            echo " checked";
                            echo "></td>";
                            echo "<td class='option-prix-paye'>" . $price . " €</td>";
                            echo "</tr>";
                            continue;
                        }
                        echo "></td>";
                        echo "<td class='option-prix-pas-paye'>" . $price . " €</td>";
                        echo "</tr>";
                        
                    }
                    echo "</table>";
                }
            }
        }
    ?>
    <div class="espaceur"></div>
	<button type="submit">Mettre à jour les options</button>
    </form>

    


    <?php require('requires/footer.php'); ?>
</body>
</html>