<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Recherche</title>
</head>
<body class="rech">
 <?php
    session_start();
    include('requires/header.php');
    afficher_header('recherche');
    require('requires/json_utilities.php');
  ?>
 <div class="recherche">
     <h2>Recherche de voyages</h2>
     <form action="resultat_recherche.php" method="GET">
         <label for="lieu"><strong>Crous à rechercher :</strong></label>
         <input type="text" id="lieu" name="lieu" placeholder="Entrez un krous que vous voulez visiter...">
         <br><br>
         <label for="date"><strong>Quand voulez-vous partir ?</strong></label>
         <input type="date" id="date" name="date">
         <br><br>
         <label for="options"><strong>Ville :</strong></label>
         <div class="selected">
             <select name="ville" id="ville">
                 <option value="">Toutes les villes</option>
                 <?php
                 $villes = recupererVilles();
                 foreach ($villes as $ville) {
                     $choix = (isset($_GET['ville']) && $_GET['ville'] == $ville) ? 'choix' : '';
                     echo "<option value='" . strtolower($ville) . "' $choix>" . $ville . "</option>";
                 }
                 ?>
             </select>
         </div>
         <br><br>
         <label for="prix"><strong>Prix :</strong></label>
         <div class="prix-range">
             <input type="number" id="prix-min" name="prix-min" min="0" placeholder="Prix minimum"
                    value="<?php echo isset($_GET['prix-min']) ? htmlspecialchars($_GET['prix-min']) : '' ?>">
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

    <?php
        $prix_min = isset($_GET['prix_min']) && is_numeric($_GET['prix_min']) ? (float)$_GET['prix_min'] : null;
        $prix_max = isset($_GET['prix_max']) && is_numeric($_GET['prix_max']) ? (float)$_GET['prix_max'] : null;
        require('requires/footer.php');
    ?>
</body>
</html>
