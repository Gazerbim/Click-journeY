<?php
require('requires/getapikey.php');
require('requires/json_utilities.php');

session_start();

if (!isset($_GET['voyage'])) {
    echo "Voyage non trouvé";
    exit;
}

$voyageId = $_GET['voyage'];
$id = $_SESSION['id'] ?? null;
if (!$id) {
    echo "Vous devez être connecté pour effectuer un paiement";
    exit;
}

if (existeDejaVoyageUtilisateur($id, $voyageId)) {
    $_SESSION["error"] = "Vous avez déjà réservé ce voyage";
    header('Location: profil.php');
    exit;
}

if (existePanierVoyageUtilisateur($id, $voyageId) && empty($_GET['maj'])) {
    $_SESSION['options'] = recupererVoyagePanierUtilisateur($id, $voyageId)['options'];
} elseif (!isset($_SESSION['options'])) {
    $_SESSION['options'] = [];
}

$optionsSelectionnees = $_SESSION['options'];
$optionsDisponibles = recupererOptionsVoyage($voyageId);
$montant = recupererPrixVoyage($voyageId);
$montantTotal = $montant;

foreach ($optionsSelectionnees as $option) {
    if (isset($optionsDisponibles[$option])) {
        $montantTotal += $optionsDisponibles[$option];
    }
}

if (!existePanierVoyageUtilisateur($id, $voyageId)) {
    ajouterVoyagePanier($id, $voyageId, []);
} else {
    modifierVoyagePanier($id, $voyageId, $optionsSelectionnees);
}

$transaction = uniqid();
$_SESSION['transaction'] = $transaction;

$vendeur = "MI-4_A";
$api_key = getAPIKey($vendeur);
$retour = "http://localhost/Click-journeY-main(35)/Click-journeY-main/retour_paiement.php?id=" . $voyageId;
$control = md5($api_key . "#" . $transaction . "#" . $montantTotal . "#" . $vendeur . "#" . $retour . "#");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement CYBank</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="light-mode">
<?php
require('requires/header.php');
afficher_header('voyages');
?>

<div class="espaceur"></div>
<div class="recherche">
    <h2>Personnaliser votre voyage</h2>

    <form>
        <input type="hidden" id="voyage-id" value="<?= $voyageId ?>">
        <table class="options-table">
            <?php foreach ($optionsDisponibles as $index => $valeur): ?>
                <tr>
                    <td><label for="<?= $index ?>"><?= $index ?> (+<?= $valeur ?> €)</label></td>
                    <td><input type="checkbox" class="option-checkbox" value="<?= $index ?>" data-price="<?= $valeur ?>"
                        <?= in_array($index, $optionsSelectionnees) ? 'checked' : '' ?>></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>

    <div class="espaceur"></div>
    <p><strong>Prix estimé :</strong> <span id="prix-estime"><?= $montantTotal ?> €</span></p>
    <p>Prix de base : <span id="prix-base"><?= $montant ?> €</span></p>
    <p>Options sélectionnées : <br><span id="recap-options">
        <?php foreach ($optionsSelectionnees as $option): ?>
            <?= $option ?> (+<?= $optionsDisponibles[$option] ?> €),
        <?php endforeach; ?>
    </span></p>
    
    <p>Montant total : <span id="montant-total"><?= $montantTotal ?> €</span></p>
    <p>Voyage : <?= recupererTitreVoyage($voyageId) ?></p>
    <p>Carte : 5555 1234 5678 9000 — Code : 555 — Expiration : 01/26</p>
    <p>Titulaire : <?= $_SESSION['prenom'] . " " . $_SESSION['nom'] ?></p>

    <div class="espaceur"></div>
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?= $transaction ?>">
        <input type="hidden" name="montant" id="montant-form" value="<?= $montantTotal ?>">
        <input type="hidden" name="vendeur" value="<?= $vendeur ?>">
        <input type="hidden" name="retour" value="<?= $retour ?>">
        <input type="hidden" name="control" id="control-input" value="<?= $control ?>">

        <button type="submit">Payer avec CYBank</button>
    </form>
    <script src="script.js"></script>
    <script src="update_options_live.js"></script>
</div>
</body>
</html>
