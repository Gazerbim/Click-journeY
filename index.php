<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <title>Rush&Krous</title>
</head>
<body class="accueil light-mode">
  <?php
    session_start();
    require('requires/header.php');
    afficher_header('index');
  ?>
    <div class="image_header">
        <div class="texte_header">
            <h1>Le tour de France des Krous pour les étudiants</h1>
        </div>
    </div>
    <div class="pres_agence">
        <h2>Présentation de l'agence</h2>
        <p>Bienvenue chez Rush&Krous ! Nous sommes une agence de voyage dédiée à offrir aux étudiants une expérience unique à travers les différentes régions de France.
            Notre mission est de vous faire découvrir les richesses culturelles, historiques et gastronomiques de chaque Krous,
            tout en vous offrant des opportunités de rencontres et d'échanges avec d'autres étudiants.</p>
        <p>Nos circuits sont spécialement conçus pour répondre aux besoins et aux attentes des étudiants, avec des tarifs avantageux, des hébergements confortables et des activités variées.
            Que vous soyez passionné par l'histoire, la nature, la cuisine ou simplement à la recherche de nouvelles aventures, nous avons le voyage qu'il vous faut.</p>
        <p>Rejoignez-nous pour une aventure inoubliable et découvrez la France comme vous ne l'avez jamais vue !</p>
    </div>
    <?php
        require('requires/json_utilities.php');
        function afficherVoyage($value){
            echo "<div class='voyage'>";
            echo "<img src='databases/voyages/" . $value['id'] . "/img/profil.jpg' alt='Voyage " . $value['id'] . "' width='100%' height='60%'>";
            echo "<p>" . htmlspecialchars($value['nom']) . "</p>";
            echo "<p>" . htmlspecialchars($value['description']) . "</p>";
            echo "<p>" . htmlspecialchars($value['tarif']) . "€</p>";
            echo "<a href='voyage_details.php?id=" . urlencode($value["id"]) . "'><button>Détails</button></a>";
            echo "</div>";
        }
        $voyages = recupererVoyages();
        $taille = count($voyages);
        $voyages_a_afficher = [];
        for ($i=0;$i<4;$i++){
            $rand = rand(0,$taille-count($voyages_a_afficher)-1);
            if(!in_array($voyages[$rand], $voyages_a_afficher)){
                $voyages_a_afficher[] = $voyages[$rand];
                continue;
            }
            $i--;
        }
        echo "<div class='recherche'>";
        echo "<h2>Quelques voyages pour vous</h2>";
        echo "<div class='voyages-container'>";
        foreach($voyages_a_afficher as $voyage){
            afficherVoyage($voyage);
        }
        echo "</div>";
        echo "</div>";
        include('requires/footer.php');
    ?>
    <script src="script.js"></script>
</body>
</html>
