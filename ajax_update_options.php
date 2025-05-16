<?php
// Prevent any HTML error output
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    require('requires/json_utilities.php');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception('Méthode non autorisée');
    }

    $voyageId = $_POST['voyageId'] ?? null;
    $options = json_decode($_POST['options'], true) ?? [];

    if (!$voyageId) {
        throw new Exception('ID voyage manquant');
    }

    // Récupérer toutes les options possibles pour le voyage
    $dispos = recupererOptionsVoyage($voyageId);
    $prixBase = recupererPrixVoyage($voyageId);
    
    // Créer un tableau avec toutes les options
    $optionsToutes = [];
    $montantTotal = $prixBase;
    $details = [];

    foreach ($dispos as $nom => $prix) {
        $optionsToutes[$nom] = in_array($nom, $options) ? "true" : "false";
        if ($optionsToutes[$nom] === "true") {
            $montantTotal += $prix;
            $details[] = ['nom' => $nom, 'prix' => $prix];
        }
    }

    // Mettre à jour la session et le panier
    $_SESSION['montant_total'] = $montantTotal;
    $_SESSION['options'] = $optionsToutes;
    $_SESSION['options2'] = $optionsToutes;
    modifierVoyagePanier($_SESSION['id'], $voyageId, $optionsToutes);

    echo json_encode([
        'success' => true,
        'prixBase' => $prixBase,
        'prixTotal' => $montantTotal,
        'options' => $details
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>