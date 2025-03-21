<?php
require('requires/getapikey.php');
require('requires/json_utilities.php');

session_start();

if (isset($_GET['voyage'])) {
    $voyageId = $_GET['voyage'];
} else {
    echo "Voyage non trouvé";
    exit;
}

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    echo "Vous devez être connecté pour effectuer un paiement";
    exit;
}

if (existeDejaVoyageUtilisateur($id, $voyageId)) {
    $_SESSION["error"] = "Vous avez déjà réservé ce voyage";
    header('Location: profil.php');
    exit;
}

// Paramètres de la transaction
$transaction = uniqid(); // Génération d'un identifiant unique pour la transaction
$montant = recupererPrixVoyage($voyageId);
$vendeur = "MI-4_A";
$retour = "http://localhost/retour_paiement.php?id=".$voyageId;

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
    <link rel="stylesheet" href="styles.css">
    <title>Paiement CYBank</title>
</head>
<body>
    <div class="recherche">
    <h2>Valider votre paiement</h2>
    <?php
    echo "Prix : ".$montant."€";
    echo "<br>";
    echo "Voyage : ".recupererTitreVoyage($voyageId);
    echo "<br>";
    echo "Numero de carte à mettre : 5555 1234 5678 9000";
    echo "<br>";
    echo "Code de sécurité : 555";
    echo "<br>";
    echo "Date d'expiration : 01/26";
    echo "<br>";
    echo "Nom du titulaire : ". $_SESSION['prenom'] . " " . $_SESSION['nom'];
    echo "<br>";
    ?>
    <div class="espaceur"></div>
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
        <input type="hidden" name="montant" value="<?php echo $montant; ?>">
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
        <input type="hidden" name="retour" value="<?php echo $retour; ?>">
        <input type="hidden" name="control" value="<?php echo $control; ?>">
        <button type="submit">Payer avec CYBank</button>
    </form>
    </div>
</body>
</html>
