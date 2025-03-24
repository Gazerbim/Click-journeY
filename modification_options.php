<?php
session_start();
if (!isset($_SESSION['id']) && empty($_SESSION['id'])) {
    echo "Vous devez être connecté pour modifier vos options.";
    exit;
}

$userId = $_SESSION['id'];

if (isset($_GET['id'])) {
    $voyageId = $_GET['id'];
} else {
    echo "Voyage non trouvé.";
    exit;
}

$filePath = "databases/utilisateurs/{$userId}/voyages.json";

if (!file_exists($filePath)) {
    echo "Le fichier de voyages n'existe pas.";
    exit;
}

$voyages = json_decode(file_get_contents($filePath), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['voyages'] as $voyageId => $voyageData) {
        foreach ($voyages as &$voyage) {
            if ($voyage['id'] == $voyageId) {
                if (isset($voyageData['options'])) {
                    foreach ($voyageData['options'] as $option => $valeur) {
                        if ($valeur === "true" || $valeur === true) {
                            $voyage['options'][$option] = true;
                        } else {
                            $voyage['options'][$option] = false;
                        }
                    }
                }

                $optionsDisponibles = [
                    'hebergement', 'restauration', 'transports', 
                    'Balade_en_Bateau_Mouche', 'Balade_à_Marseille'
                ];

                foreach ($optionsDisponibles as $option) {
                    if (!isset($voyageData['options'][$option])) {
                        $voyage['options'][$option] = false;
                    }
                }
            }
        }
    }

    if (file_put_contents($filePath, json_encode($voyages, JSON_PRETTY_PRINT))) {
        header('Location: voyage_option.php?id=' . urlencode($voyageId));
        exit;
    } else {
        echo "Une erreur s'est produite lors de la sauvegarde des modifications.";
    }
}
?>
