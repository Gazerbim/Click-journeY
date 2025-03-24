<?php
session_start();
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    exit("Vous devez être connecté pour modifier vos options.");
}

$userId = $_SESSION['id'];
$voyageId = $_GET['id'] ?? exit("Voyage non trouvé.");

$filePath = "databases/utilisateurs/{$userId}/voyages.json";

if (!file_exists($filePath)) {
    exit("Le fichier de voyages n'existe pas.");
}

$voyages = json_decode(file_get_contents($filePath), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($voyages as &$voyage) {
        if ($voyage['id'] === $voyageId) {
            foreach ($voyage['options'] as $option => &$valeur) {
                if (isset($_POST['Options']) && in_array($option, $_POST['Options'])) {
                    $valeur = "true";
                } else {
                    $valeur = "false";
                }
            }
            break;
        }
    }
    file_put_contents($filePath, json_encode($voyages, JSON_PRETTY_PRINT));
    header("Location: voyage_option.php?id=" . urlencode($voyageId));
    exit;
}
?>
