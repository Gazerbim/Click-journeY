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



if(existePanierVoyageUtilisateur($_SESSION['id'], $voyageId) && empty($_GET['maj'])){
    $_SESSION['options'] = recupererVoyagePanierUtilisateur($_SESSION['id'], $voyageId)['options'];
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
if(!existePanierVoyageUtilisateur($_SESSION['id'], $voyageId) && !existeDejaVoyageUtilisateur($id, $voyageId)){
    $optionsSelectionnees = [];
    $_SESSION['options'] = $optionsSelectionnees;
}

foreach ($optionsSelectionnees as $option) {
    if (isset($optionsDisponibles[$option])) {
        $montantTotal += $optionsDisponibles[$option];  // Ajouter le coût de l'option sélectionnée
    }
}

if (!existePanierVoyageUtilisateur($_SESSION['id'], $voyageId)) { // le voyage n'est pas dans le panier => on l'ajoute
    ajouterVoyagePanier($id, $voyageId, []); // Ajout du voyage au panier de l'utilisateur
}else{
    modifierVoyagePanier($id, $voyageId, $optionsSelectionnees); // Modification du voyage dans le panier de l'utilisateur
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <link rel="stylesheet" href="styles.css">
    <title>Paiement CYBank</title>
</head>
<body class="light-mode">
    <?php
        require('requires/header.php');
        afficher_header('voyages');
    ?>
    <div class="espaceur"></div>
    <div class="recherche">
    <h2>Personaliser votre voyage</h2>
    

    <form action="update_options.php" method="POST" class="maj_options">
    <input type="hidden" name="voyageId" value="<?php echo $voyageId; ?>">
    <?php
    echo "<p><strong> Options possibles : </strong></p><br>";
    
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
    echo "<p>Prix de base : ".$montant."€</p>";
    echo "<p>Options sélectionnées : <br>";
    foreach ($optionsSelectionnees as $option) {
        echo $option . " (+". $optionsDisponibles[$option] ." €), ";
    }
    echo "</p>";
    $control = md5($api_key . "#" . $transaction . "#" . $montantTotal . "#" . $vendeur . "#" . $retour . "#");
    echo "<p>Montant total : ".$montantTotal."€</p>";  // Affichage du montant total avec les options
    echo "<p>Voyage : ".recupererTitreVoyage($voyageId)."</p>";
    echo "<p>Numero de carte à mettre : 5555 1234 5678 9000</p>";
    echo "<p>Code de sécurité : 555</p>";
    echo "<p>Date d'expiration : 01/26</p>";
    echo "<p>Nom du titulaire : ". $_SESSION['prenom'] . " " . $_SESSION['nom']."</p>";
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
