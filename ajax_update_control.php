<?php
require('requires/getapikey.php');
session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$voyageId = $_POST['voyageId'] ?? null;
$options = $_POST['options'] ?? [];

if (!$voyageId) {
    echo json_encode(['success' => false, 'message' => 'ID voyage manquant']);
    exit;
}

// Récupérer le montant total actuel depuis la session
$montantTotal = $_SESSION['montant_total'] ?? 0;

// Générer la nouvelle valeur de contrôle
$vendeur = "MI-4_A";
$api_key = getAPIKey($vendeur);
$transaction = $_SESSION['transaction'];
$retour = "http://localhost/retour_paiement.php?id=" . $voyageId;

$control = md5($api_key . "#" . $transaction . "#" . $montantTotal . "#" . $vendeur . "#" . $retour . "#");

echo json_encode([
    'success' => true,
    'control' => $control
]);
?>