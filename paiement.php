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

$transaction = uniqid();
$montant = recupererPrixVoyage($voyageId);  // Prix de base
$vendeur = "MI-4_A";

$api_key = getAPIKey($vendeur);

if (!preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
    die("Erreur : Clé API invalide !");
}

// Calculer le montant total en ajoutant le prix des options
$optionsDisponibles = recupererOptionsVoyage($voyageId);
$montantTotal = $montant; // Commencer avec le prix de base

$optionsSelectionnees = $_SESSION['options'] ?? [];
foreach ($optionsSelectionnees as $option) {
    if (isset($optionsDisponibles[$option])) {
        $montantTotal += $optionsDisponibles[$option];  // Ajouter le coût de l'option sélectionnée
    }
}

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
    

    <form action="update_options.php" method="POST" class="maj_options">
    <input type="hidden" name="voyageId" value="<?php echo $voyageId; ?>">
    <?php
    echo "<strong> Options possibles : </strong><br>";
    
    echo "<table class='options-table'>";
    foreach ($optionsDisponibles as $index => $valeur) { 
        echo "<tr class='option-row'>";
        echo "<td><label for='$index' class='option-label'>$index (+$valeur €)</label></td>";
        echo "<td><input type='checkbox' name='options[]' value='$index' class='option-checkbox'";
        if (isset($_SESSION['options']) && in_array($index, $_SESSION['options'])) {
            echo " checked";
        }
        echo "></td>";
        echo "</tr>";
    }
    echo "</table>";
    

    
    
        
$optionsParams = [];
$optionsRetour = [];
foreach ($optionsDisponibles as $index => $valeur) {
    $etat = in_array($index, $optionsSelectionnees) ? 'true' : 'false';
    $optionsParams[] = "$index=$etat";
    $optionsRetour[$index] = $etat;
}

$_SESSION['retour'] = $optionsRetour;
$retour = "http://localhost/retour_paiement.php?id=" . $voyageId;



    ?>
    <div class="espaceur"></div>
    <?php
    echo "Prix de base : ".$montant."€";
    echo "<br>";
    echo "Options sélectionnées : ";
    foreach ($optionsSelectionnees as $option) {
        echo $option . " (+". $optionsDisponibles[$option] ." €), ";
    }
    $control = md5($api_key . "#" . $transaction . "#" . $montantTotal . "#" . $vendeur . "#" . $retour . "#");
    echo "<br>";
    echo "Montant total : ".$montantTotal."€";  // Affichage du montant total avec les options
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
    <button type="submit">Mettre à jour les options</button>
    </form>    
        
    <div class="espaceur"></div>
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
        <input type="hidden" name="montant" value="<?php echo $montantTotal; ?>">  <!-- Montant total -->
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
        <input type="hidden" name="retour" value="<?php echo $retour; ?>">
        <input type="hidden" name="control" value="<?php echo $control; ?>">
        <button type="submit">Payer avec CYBank</button>
    </form>
    </div>
</body>
</html>
