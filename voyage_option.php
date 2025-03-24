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
        require('requires/json_utilities.php');
        if (!isset($_SESSION['id']) && empty($_SESSION['id'])) {
            header('Location: connexion.php');
            exit;
        }
        
        if (isset($_GET['id'])) {
            $voyageId = $_GET['id'];
        } else {
            echo "Voyage non trouvé";
            exit;
        }
        
        $user_id = $_SESSION['id'];
        $voyages = recupererVoyagesUtilisateur($user_id);

        if (is_string($voyages)) {
            $voyages = json_decode($voyages, true);
        }

        if (!is_array($voyages)) {
            echo "Aucun voyage trouvé.";
            exit;
        }

        // Récupérer les informations du voyage avec l'ID
        $voyage = recupererVoyageAvecId($voyageId);
        $etapes = recupererEtapesVoyage($voyageId);
        $nom = $voyage['nom'];

        $descriptionFile = "databases/voyages/{$voyageId}/description.txt";
        $dateFile = "databases/voyages/{$voyageId}/date.txt";

        $description = file_exists($descriptionFile) ? file_get_contents($descriptionFile) : "Description non disponible.";
        $date = file_exists($dateFile) ? file_get_contents($dateFile) : "Date non disponible.";
    ?>

    <div class="voyage_detail">
        <h1><?php echo nl2br($nom); ?></h1>
        <h3><?php echo nl2br($description); ?></h3>
        <h3><?php echo nl2br($date); ?></h3>
        <img src="databases/voyages/<?php echo $voyageId; ?>/img/profil.jpg" alt="image1" width="100%" height="20%">
    </div>
    
    <form action="modification_options.php?id=<?php echo htmlspecialchars($voyageId); ?>" method="POST" class="recherche">
    <?php foreach ($voyages as $voyage): ?>
        <?php 
            $voyageId = $voyage['id'];
            $options = $voyage['options']; 
        ?>
        <input type="hidden" name="voyages[<?php echo htmlspecialchars($voyageId); ?>][id]" value="<?php echo htmlspecialchars($voyageId); ?>">

        <?php foreach ($options as $option => $valeur): ?>
            <div>
                <input type="checkbox" id="opt_<?php echo htmlspecialchars($voyageId . '_' . $option); ?>"
                    name="voyages[<?php echo htmlspecialchars($voyageId); ?>][options][<?php echo htmlspecialchars($option); ?>]" value="true"
                    <?php if ($valeur === "true" || $valeur === true) echo "checked"; ?>>
                <label for="opt_<?php echo htmlspecialchars($voyageId . '_' . $option); ?>">
                    <?php echo htmlspecialchars(str_replace('_', ' ', $option)); ?>
                </label>
            </div>
        <?php endforeach; ?>

        <br>
    <?php endforeach; ?>

    <button type="submit">Mettre à jour les options</button>
</form>



    <?php require('requires/footer.php'); ?>
</body>
</html>
