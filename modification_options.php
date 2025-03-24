<?php
session_start();

include('requires/getapikey.php');

// Récupération des paramètres envoyés par CYBank
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_recu = $_GET['control'] ?? '';
$idVoyage = $_GET['id'] ?? '';
$options = $_SESSION['retour'] ?? [];

if (empty($transaction) || empty($montant) || empty($vendeur) || empty($statut) || empty($control_recu) || empty($idVoyage)) {
    die("Erreur : Paramètres manquants !");
}
$api_key = getAPIKey($vendeur);
if (!preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
    die("Erreur : Clé API invalide !");
}

$control_calculé = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

if ($control_calculé !== $control_recu) {
    die("Erreur : Contrôle de sécurité invalide !");
}

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



if($statut === "accepted"){
    foreach ($voyages as &$voyage) {
        if ($voyage['id'] === $voyageId) {
            foreach ($voyage['options'] as $option => &$valeur) {
                if (isset($_SESSION['options']) && in_array($option, $_SESSION['options'])) {
                    $valeur = "true";
                } else {
                    $valeur = "false";
                }
            }
            break;
        }
    
    }
    file_put_contents($filePath, json_encode($voyages, JSON_PRETTY_PRINT));
    unset($_SESSION['options']);
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Résultat du paiement</title>
</head>
<body>
    <div class="recherche">
        <h2>Résultat du paiement</h2>
        <?php if ($statut === "accepted") : ?>
            <p style="color: green;">✅ Paiement accepté !</p>
            <p>Transaction ID : <?php echo htmlspecialchars($transaction); ?></p>
            <p>Montant payé : <?php echo htmlspecialchars($montant); ?> €</p>
            <p>Les options du voyage ont été modifiées</p>
        <?php else : ?>
            <p style="color: red;">❌ Paiement refusé.</p>
        <?php endif; ?>
        <a href="voyage_option.php?id=<?php echo $idVoyage ?>">Retour au Voyage</a>
    </div>
</body>
</html>