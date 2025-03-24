<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Voyage</title>
</head>
<body>
<?php
session_start();
require('requires/getapikey.php');
require('requires/json_utilities.php');

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    $_SESSION["error"] = "Vous devez être connecté pour effectuer un paiement";
    header('Location: connexion.php');
    exit;
}

if (isset($_GET['id'])) {
    $voyageId = $_GET['id'];
} else {
    $_SESSION["error"] = "Voyage non trouvé";
    header('Location: profil.php');
    exit;
}

$voyages = recupererVoyagesUtilisateur($id);
$optionsPrices = recupererOptionsVoyage($voyageId);
$selectedOptions = [];
$coutOptions = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $options = $_POST['Options'] ?? [];
    
    foreach ($voyages as $v) {
        if ($v['id'] == $voyageId) {
            $voyage = $v;
            break;
        }
    }
    
    foreach ($voyage["options"] as $key => $option) {
        if (!in_array($key, $options) && $option == "true") {
            $coutOptions -= $optionsPrices[$key];
        }
        elseif (in_array($key, $options) && $option == "false") {
            $coutOptions += $optionsPrices[$key];
            $selectedOptions[$key] = $optionsPrices[$key];
        }
    }
    
    $transaction = uniqid();
    $vendeur = "MI-4_A";
    $api_key = getAPIKey($vendeur);
    $retour = "http://localhost/modification_options.php?id=" . urlencode($voyageId);
    
    if (!preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
        die("Erreur : Clé API invalide !");
    }
    if ($coutOptions == 0) {
        $coutOptions = 0.01;
    }
    $control = md5($api_key . "#" . $transaction . "#" . $coutOptions . "#" . $vendeur . "#" . $retour . "#");
    $_SESSION['options'] = $options;
    
    
    echo "<div class='recherche'>";
    echo "<h2>Détails du paiement</h2>";
    if ($coutOptions < 0) {
        $coutOptions = -$coutOptions;
        echo "<p>Vous allez être remboursé de : <strong>$coutOptions €</strong></p>";
        $coutOptions = -$coutOptions;
    } else {
        echo "<p>Le montant total à payer est de : <strong>$coutOptions €</strong></p>";
    }
    
    
    if (!empty($selectedOptions)) {
        echo "<h3>Options achetées :</h3><ul>";
        foreach ($selectedOptions as $option => $prix) {
            echo "<li>$option : $prix €</li>";
        }
        echo "</ul>";
    }
}
?>
<form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
    <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
    <input type="hidden" name="montant" value="<?php echo $coutOptions; ?>">
    <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
    <input type="hidden" name="retour" value="<?php echo $retour; ?>">
    <input type="hidden" name="control" value="<?php echo $control; ?>">
    <button type="submit" class="bouton-vert" >Payer Options</button>
</form>
<div class="espaceur"></div>
<a href="voyage_option.php?id=<?php echo $voyageId; ?>"><button>Retour aux options</button></a>
</div>
</body>
