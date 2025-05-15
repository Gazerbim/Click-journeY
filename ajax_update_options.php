<?php
require('requires/json_utilities.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$voyageId = $_POST['voyageId'] ?? null;
$options = $_POST['options'] ?? [];

if (!$voyageId) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

// Récupérer toutes les options possibles pour le voyage
$dispos = recupererOptionsVoyage($voyageId); // format: [nom => prix]

// Créer un tableau avec toutes les options : true si cochée, false sinon
$optionsToutes = [];
foreach ($dispos as $nom => $prix) {
    $optionsToutes[$nom] = in_array($nom, $options) ? "true" : "false";
}

// Enregistrer toutes les options dans la session
$_SESSION['options'] = $optionsToutes;

$_SESSION['options2'] = $optionsToutes;

// Calculer le prix total
$base = recupererPrixVoyage($voyageId);
$total = $base;
$details = [];

foreach ($optionsToutes as $nom => $etat) {
    if ($etat === "true" && isset($dispos[$nom])) {
        $total += $dispos[$nom];
        $details[] = ['nom' => $nom, 'prix' => $dispos[$nom]];
    }
}

echo json_encode([
    'success' => true,
    'prixBase' => $base,
    'prixTotal' => $total,
    'options' => $details
]);
