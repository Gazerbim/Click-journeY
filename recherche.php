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
         <label for="date_debut"><strong>Période de voyage souhaitée :</strong></label>
         <div class="date-range" style="display: flex; align-items: center; gap: 20px; margin-top: 20px">
             <label for="date_debut">Du</label>
             <input type="date" id="date_debut" name="date_debut"
                    value="<?php echo isset($_GET['date_debut']) ? htmlspecialchars($_GET['date_debut']) : ''; ?>">
             <label for="date_fin">Au</label>
             <input type="date" id="date_fin" name="date_fin"
                    value="<?php echo isset($_GET['date_fin']) ? htmlspecialchars($_GET['date_fin']) : ''; ?>">
         </div>
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

    <?php
        require('requires/footer.php');
    ?>
</body>
</html>
