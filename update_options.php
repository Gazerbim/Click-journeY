<?php
require('requires/json_utilities.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['voyageId']) || empty($_POST['voyageId'])) {
        die("ID voyage manquant.");
    }

    $voyageId = $_POST['voyageId'];
    $options = $_POST['options'] ?? [];

    $file_path = 'databases/voyages/' . $voyageId . '/options.json';

    if (file_exists($file_path)) {
        $json_data = file_get_contents($file_path);
        $optionsDisponibles = json_decode($json_data, true);
    } else {
        die("Le fichier options.json est manquant pour ce voyage.");
    }

    foreach ($optionsDisponibles['etapes'] as $index => &$etape) {
        if (isset($options['hebergement'][$index])) {
            $etape['hebergement'] = true;
        } else {
            $etape['hebergement'] = false;
        }

        if (isset($options['restauration'][$index])) {
            $etape['restauration'] = true;
        } else {
            $etape['restauration'] = false;
        }

        if (isset($options['transports'][$index])) {
            $etape['transports'] = true;
        } else {
            $etape['transports'] = false;
        }

        if (isset($options['activites'][$index])) {
            foreach ($etape['activites'] as &$activiteData) {
                if (in_array($activiteData['nom'], $options['activites'][$index])) {
                    $activiteData['option'] = true;
                }

                if (!in_array($activiteData['nom'], $options['activites'][$index])) {
                    $activiteData['option'] = false;
                }
            }
        }
    }

    file_put_contents($file_path, json_encode($optionsDisponibles, JSON_PRETTY_PRINT));

    header("Location: payement.php?voyage=$voyageId");
    exit;
} else {
    die("Accès non autorisé.");
}
?>
