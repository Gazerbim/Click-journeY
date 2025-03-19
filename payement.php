<?php
require('requires/getapikey.php');

// Paramètres de la transaction
$transaction = uniqid(); // Génération d'un identifiant unique pour la transaction
$montant = 1000.50;
$vendeur = "MI-4_A";
$retour = "http://localhost/retour_paiement.php";

// Récupération de la clé API
$api_key = getAPIKey($vendeur);

// Vérification de la clé API
if (!preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
    die("Erreur : Clé API invalide !");
}

// Génération de la valeur de contrôle
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement CYBank</title>
</head>
<body>
    <h2>Valider votre paiement</h2>
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
        <input type="hidden" name="montant" value="<?php echo $montant; ?>">
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
        <input type="hidden" name="retour" value="<?php echo $retour; ?>">
        <input type="hidden" name="control" value="<?php echo $control; ?>">
        <button type="submit">Payer avec CYBank</button>
    </form>
</body>
</html>
