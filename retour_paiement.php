<?php
require('requires/getapikey.php');
require('requires/json_utilities.php');

// Récupération des paramètres envoyés par CYBank
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_recu = $_GET['control'] ?? '';

// Vérification que les paramètres sont bien reçus
if (empty($transaction)|| empty($montant) || empty($vendeur) || empty($statut) || empty($control_recu)) {
        die("Erreur : Paramètres manquants !");
}

// Récupération de l'API key
$api_key = getAPIKey($vendeur);

// Vérification de la clé API
if (!preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
    die("Erreur : Clé API invalide !");
}

// Recalcul de la valeur de contrôle
$control_calculé = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

// Vérification de l'intégrité des données
if ($control_calculé !== $control_recu) {
    die("Erreur : Contrôle de sécurité invalide !");
}

if ($statut === "accepted") {
    // Ajout du voyage à la liste des voyages de l'utilisateur
    session_start();
    $id = $_SESSION['id'];
    $idVoyage = $_GET['id'];
    $date = date("d-m-Y");
    ajouterVoyageUtilisateur($id, $idVoyage, $date, $transaction);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat du paiement</title>
</head>
<body>
    <h2>Résultat du paiement</h2>

    <?php if ($statut === "accepted") : ?>
        <p style="color: green;">✅ Paiement accepté !</p>
        <p>Transaction ID : <?php echo htmlspecialchars($transaction); ?></p>
        <p>Montant payé : <?php echo htmlspecialchars($montant); ?> €</p>
        <p>Le voyage a été ajouté à votre compte.</p>
    <?php else : ?>
        <p style="color: red;">❌ Paiement refusé.</p>
    <?php endif; ?>

    <a href="index.php">Retour à l'Accueil</a>
</body>
</html>
