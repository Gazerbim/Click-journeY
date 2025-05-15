<?php
require('requires/getapikey.php');
require('requires/json_utilities.php');
session_start();
header('Content-Type: application/json');

$voyageId = $_POST['voyageId'] ?? null;
$options = $_POST['options'] ?? [];

if (!$voyageId) {
    echo json_encode(['success' => false, 'message' => 'ID voyage manquant']);
    exit;
}

$_SESSION['options'] = $options;

$base = recupererPrixVoyage($voyageId);
$dispos = recupererOptionsVoyage($voyageId);
$total = $base;

foreach ($options as $opt) {
    if (isset($dispos[$opt])) {
        $total += $dispos[$opt];
    }
}

$transaction = $_SESSION['transaction'] ?? uniqid(); // assure continuité
$_SESSION['transaction'] = $transaction;
$vendeur = "MI-4_A";
$api_key = getAPIKey($vendeur);
$retour = "http://localhost/Click-journeY-main(35)/Click-journeY-main/retour_paiement.php?id=" . $voyageId;

$control = md5($api_key . "#" . $transaction . "#" . $total . "#" . $vendeur . "#" . $retour . "#");

echo json_encode([
    'success' => true,
    'control' => $control
]);
