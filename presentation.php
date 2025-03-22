<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Présentation</title>
</head>
<body>
 <?php
    session_start();
    include('requires/header.php');
    afficher_header('presentation');
  ?>
    <div class="presentation">
        <h1>Bienvenue chez Rush&Krous : L'agence de voyage des étudiants !</h1>

        <h3>Chez le Crous, nous avons toujours eu à cœur d'accompagner les étudiants dans leur quotidien. 
            Aujourd'hui, nous allons encore plus loin en lançant Rush&Krous, une agence de voyage conçue spécialement pour les étudiants.</h3>
        
        <h2>Qu'est-ce que Rush&Krous ?</h2>
        
        <p>Rush&Krous est une initiative unique qui permet aux étudiants de découvrir la richesse des régions françaises tout en bénéficiant des infrastructures du Krous. 
            Grâce à nos voyages, explorez différentes villes étudiantes, goûtez à des spécialités locales dans les restaurants universitaires et rencontrez des étudiants de toute la France.</p>

        <img src="images/lesKrous.jpg" alt="Les Krous" width="100%" height="20%">
        
        <h2>Nos offres de voyage</h2>

        <h4>Nous proposons des séjours accessibles et adaptés aux besoins des étudiants :</h4>


            <h1>Voyages culturels</h1>
            <h4 class="center">Partez à la découverte du patrimoine historique et artistique des villes où le Krous est présent.</h4>
            <div class="image">
                <img src="images/Paris.jpg" alt="Paris">
                <img src="images/Marseille.jpg" alt="Marseille">
                <img src="images/Bordeaux.jpg" alt="Bordeaux">
                <img src="images/Lille.jpg" alt="Lille">
            </div>
            <h1>Séjours gastronomiques</h1>
            <h4 class="center">Profitez des spécialités culinaires locales dans nos restaurants universitaires.</h4>
            <div class="image1">
                <img src="images/Resto1.jpg" alt="Restaurant Krous">
                <img src="images/Resto3.jpg" alt="Restaurant Krous">
                <img src="images/Resto2.jpg" alt="Restaurant Krous">
            </div>
            <h1>Échanges étudiants</h1>
            <h4 class="center">Rencontrez des étudiants d'autres régions et partagez vos expériences.</h4>
            <img src="images/Etudiants.jpg" alt="Étudiants" width="100%" height="20%">
            <h1>Éco-découvertes</h1>
            <h4 class="center">Voyagez de manière responsable et explorez des initiatives durables mises en place par les Krous.</h4>
            <table>
                <tr>
                    <img src="images/autocar.jpg" alt="Autocar" width="50%" height="20%">
                    <img src="images/tgv.jpg" alt="TGV" width="50%" height="20%">
                </tr>
                
            </table>
        
        </ul>
    </div>
    
    <div class="recherche">
        <h2>Recherche rapide</h2>
        <p>Vous cherchez un voyage en particulier ? Utilisez la recherche rapide pour trouver la destination qui vous convient :</p>
        <form action="liste_voyages.php" method="GET">
            <label for="mot_clef">Recherchez :</label>
            <input type="text" id="mot_clef" name="mot_clef" placeholder="Entrez un mot-clé...">
            <button type="submit">Rechercher</button>
        </form>
    </div>
    <?php
    include('requires/footer.php');
    ?>
</body>
</html>
