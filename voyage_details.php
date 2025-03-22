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
        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            if(!existeDejaVoyageUtilisateur($_SESSION['id'], $_GET['id'])){
                echo "<a href='payement.php?voyage=".$_GET['id']."' class='buy-button'><button>Acheter</button></a>";
            } 
        }
        require('requires/header.php');
        afficher_header('');
    ?>

<?php 
    
    if (isset($_GET['id'])) {
        $voyageId = $_GET['id'];
    } else {
        echo "Voyage non trouvé";
        exit;
    }

        require_once 'requires/json_utilities.php';
    $voyage = recupererVoyageAvecId($voyageId);
    $etapes = recupererEtapesVoyage($voyageId);
    $nom = $voyage['nom'];

        $descriptionFile = "databases/voyages/{$voyageId}/description.txt";
    $dateFile = "databases/voyages/{$voyageId}/date.txt";
    
    $tarifFile = "databases/voyages/{$voyageId}/tarif.txt";
    $faqFile = "databases/voyages/{$voyageId}/faq.txt";
    $avisFile = "databases/voyages/{$voyageId}/avis.txt";
    $optionsfile = "databases/voyages/{$voyageId}/options.json";
    
    $description = file_exists($descriptionFile) ? file_get_contents($descriptionFile) : "Description non disponible.";
    $date = file_exists($dateFile) ? file_get_contents($dateFile) : "Date non disponible.";
   
    $tarif = file_exists($tarifFile) ? file_get_contents($tarifFile) : "Tarif non disponible.";
    $faq = file_exists($faqFile) ? file_get_contents($faqFile) : "FAQ non disponible.";
    $avis = file_exists($avisFile) ? file_get_contents($avisFile) : "Avis non disponible.";
?>

    <div class="voyage_detail">
        <h1> <?php echo nl2br($nom); ?> </h1>
        <h3> <?php echo nl2br($description); ?> </h3>
        <h3> <?php echo nl2br($date); ?> </h3>
        <img src="databases/voyages/<?php echo $voyageId; ?>/img/profil.jpg" alt="image1" width="100%" height="20%">

        <h2> Programme du voyage ! </h2>
        <?php
        if (!empty($etapes)) {
            $options = json_decode(file_get_contents($optionsfile), true); 
            foreach ($etapes as $index => $etape) {
                $numEtape = $index; 
                
                echo "<h3>Étape " . ($numEtape + 1) . " :</h3>";
                echo "<pre>" . file_get_contents("databases/voyages/{$voyageId}/etapes/jour" . ($numEtape + 1) . ".txt") . "</pre>";
                
                if (isset($options['etapes'][$numEtape])) {
                    echo "<h3>Options disponibles :</h3>";
                    
                    echo "<pre>Hébergement</pre>";
                    echo "<pre>Restauration</pre>";
                    echo "<pre>Transports</pre>";
                    
                    if (!empty($options['etapes'][$numEtape]['activites'])) {
                        echo "<h4>Activités :</h4>";
                        foreach ($options['etapes'][$numEtape]['activites'] as $activite) {
                            echo "<pre>" . $activite['nom'] . "</pre>";
                        }
                    }
                }
                echo "<img src='databases/voyages/{$voyageId}/img/image_etape" . ($numEtape + 1) . ".jpg' alt='image_etape" . ($numEtape + 1) . "' width='100%' height='20%'>";
            }
        } else {
            echo "<p>Aucune étape disponible pour ce voyage.</p>";
        }
        ?>
        <div class="section">
            <h2>Tarif</h2>
            <p><?php echo nl2br($tarif); ?></p>
        </div>
        <div class="section">
            <h2>Avis</h2>
            <p><?php echo nl2br($avis); ?></p>
        </div>
        <div class="section">
            <h2>FAQ</h2>
            <p><?php echo nl2br($faq); ?></p>
        </div>
    </div>
    <?php
        require('requires/footer.php');
    ?>
</body>
</html>
