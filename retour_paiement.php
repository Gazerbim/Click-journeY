<?php
require('requires/getapikey.php');
require('requires/json_utilities.php');

// Récupération des paramètres envoyés par CYBank
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_recu = $_GET['control'] ?? '';
$idVoyage = $_GET['id'] ?? '';
$options = $_GET ?? [];
unset($options['transaction']);
unset($options['montant']);
unset($options['vendeur']);
unset($options['status']);
unset($options['control']);
unset($options['id']);

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

if ($statut === "accepted") {
    session_start();
    $id = $_SESSION['id'];
    $date = date("d-m-Y");
    
    if (!existeDejaTransaction($id, $transaction)) {
        ajouterVoyageUtilisateur($id, $idVoyage, $date, $transaction);
        ajouterOptionUtilisateur($id, $idVoyage, $options);
    }
    unset($_SESSION['options']); // enlever les options de la session
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
            <p>Le voyage a été ajouté à votre compte avec les options sélectionnées.</p>
        <?php else : ?>
            <p style="color: red;">❌ Paiement refusé.</p>
        <?php endif; ?>
        <a href="index.php">Retour à l'Accueil</a>
    </div>
</body>
</html>
