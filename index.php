<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <title>Rush&Krous</title>
</head>
<body class="accueil">
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
        include('requires/footer.php');
    ?>
</body>
</html>
