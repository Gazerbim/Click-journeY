<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Recherche</title>
</head>
<body class="rech light-mode" >
<?php
session_start();
require("requires/json_utilities.php");
include('requires/header.php');
afficher_header('recherche');
?>
    <div class="recherche">
        <h2>Recherche de voyages</h2>
        <form action="resultat_recherche.php" method="GET">
            <label for="lieu"><strong>Crous à rechercher :</strong></label>
            <input type="text" id="lieu" name="lieu" placeholder="Entrez un krous que vous voulez visiter...">
            <br><br>
            <label for="date_debut"><strong>Période de voyage souhaitée :</strong></label>
            <div class="date-range" style="display: flex; align-items: center; gap: 20px; margin-top: 20px">
                <label for="date_debut">Du</label>
                <input type="date" id="date_debut" name="date_debut"
                       value="<?php echo isset($_GET['date_debut']) ? htmlspecialchars($_GET['date_debut']) : ''; ?>">
                <label for="date_fin">Au</label>
                <input type="date" id="date_fin" name="date_fin"
                       value="<?php echo isset($_GET['date_fin']) ? htmlspecialchars($_GET['date_fin']) : ''; ?>">
            </div>
            <br><br>
            <label for="prix"><strong>Prix :</strong></label>
            <div class="prix-range">
                <input type="number" id="prix_min" name="prix_min" min="0" placeholder="Prix minimum"
                       value="<?php echo isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : ''; ?>">
                <span>à</span>
                <input type="number" id="prix_max" name="prix_max" min="0" placeholder="Prix maximum"
                       value="<?php echo isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : ''; ?>">
                <span>€</span>
            </div>
            <label for="etapes"><strong>Étapes :</strong></label>
            <div class="checkbox-groupe">
                <input type="checkbox" id="etapes" name="etapes[]" value="2">2
                <input type="checkbox" id="etapes" name="etapes[]" value="3">3
                <input type="checkbox" id="etapes" name="etapes[]" value="4">4
                <input type="checkbox" id="etapes" name="etapes[]" value="5">5
            </div>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <div class="liste_voyages">
        <h2>Résultats de la recherche</h2>

        <div class="tri-options">
            <label for="tri"><strong>Trier par :</strong></label>
            <select id="tri">
                <option value="default">Par défaut</option>
                <option value="prix-c">Prix croissant</option>
                <option value="prix-dc">Prix décroissant</option>
                <option value="nom-c">Nom (A-Z)</option>
                <option value="nom-dc">Nom (Z-A)</option>
                <option value="date-c">Date (plus proche)</option>
                <option value="date-dc">Date (plus lointaine)</option>
                <option value="etapes-c">Nombre d'étapes (croissant)</option>
                <option value="etapes-dc">Nombre d'étapes (décroissant)</option>
            </select>
        </div>

        <div class="voyages-container">
        <?php

        function afficherVoyage($value){
            $etapes = count(recupererEtapesVoyage($value['id']));
            $date = avoirDateVoyage($value['id']);

            echo "<div class='voyage' data-etapes='$etapes' data-date='$date'>";
            echo "<img src='databases/voyages/" . $value['id'] . "/img/profil.jpg' alt='Voyage " . $value['id'] . "' width='100%' height='60%'>";
            echo "<p>" . htmlspecialchars($value['nom']) . "</p>";
            echo "<p>" . htmlspecialchars($value['description']) . "</p>";
            echo "<p>" . htmlspecialchars($value['tarif']) . "€</p>";
            echo "<a href='voyage_details.php?id=" . urlencode($value["id"]) . "'><button>Détails</button></a>";
            echo "</div>";
        }



        if(isset($_GET['lieu'])){
            $lieu = $_GET['lieu'];
            $lieu = explode(" ", $lieu);
        }
        else{
            $lieu = [""];
        }
        $date_debut = $_GET['date_debut'] ?? "";
        $date_fin = $_GET['date_fin'] ?? "";
        $villeSelectionnee = $_GET['ville'] ?? "";
        $prix_min = isset($_GET['prix_min']) && is_numeric($_GET['prix_min']) ? (float)$_GET['prix_min'] : null;
        $prix_max = isset($_GET['prix_max']) && is_numeric($_GET['prix_max']) ? (float)$_GET['prix_max'] : null;
        $etapes = $_GET['etapes'] ?? [];
        $voyages = recupererVoyages();
        $voyagesSelectionnes = [];
        foreach ($voyages as $value) {
            $resultat = true;
            $mots = avoirListeMotsVoyage($value['id']);
            $dates = avoirDateVoyage($value['id']);
            foreach($lieu as $mot_clef){
                if (!(strpos(strtolower($mots), strtolower($mot_clef))!==false)){
                    $resultat = false;
                    break;
                }
                if (!empty($etapes)){
                    $etapesMatch = false;
                    foreach($etapes as $etape){
                        if($etape == count(recupererEtapesVoyage($value['id']))){
                            $etapesMatch = true;
                            break;
                        }
                    }
                    if(!$etapesMatch){
                        $resultat = false;
                    }
                }
            }
            if(!empty($villeSelectionnee)) {
                $etapesVoyage = recupererEtapesVoyage($value['id']);
                $villeMatch = false;
                foreach($etapesVoyage as $etape) {
                    if(strtolower($etape['ville']) == strtolower($villeSelectionnee)) {
                        $villeMatch = true;
                        break;
                    }
                }
                if(!$villeMatch) {
                    $resultat = false;
                }
            }
            if($date_debut != "" || $date_fin != ""){
                $dateVoyage = DateTime::createFromFormat('d/m/Y', $dates);
                if($date_debut != ""){
                    $dateDebut = new DateTime($date_debut);
                    if($dateVoyage < $dateDebut){
                        $resultat = false;
                    }
                }
                if($date_fin != ""){
                    $dateFin = new DateTime($date_fin);
                    if($dateVoyage > $dateFin){
                        $resultat = false;
                    }
                }
            }
            if($resultat && $prix_min !== null) {
                $prix = (float)str_replace(',', '.', $value['tarif']);
                if($prix < $prix_min) {
                    $resultat = false;
                }
            }
            if($resultat && $prix_max !== null) {
                $prix = (float)str_replace(',', '.', $value['tarif']);
                if($prix > $prix_max) {
                    $resultat = false;
                }
            }
            if($resultat){
                $voyagesSelectionnes[] = $value;
            }
        }

        foreach ($voyagesSelectionnes as $value) {
            afficherVoyage($value);
        }
        ?>
        </div>
    </div>
<script src="tri_recherche.js"></script>
<script src="script.js"></script>
<?php
require('requires/footer.php');
?>
</body>
</html>
