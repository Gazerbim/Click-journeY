<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Recherche</title>
</head>
<body class="rech" >
 <?php
    session_start();
    include('requires/header.php');
    afficher_header('recherche');
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
                <label for="options"><strong>Options :</strong></label>
                <div class="checkbox-groupe">
                    <input type="checkbox" id="options" name="options[]" value="a">Meilleur Note
                    <input type="checkbox" id="options" name="options[]" value="b">Pire Note
                    <input type="checkbox" id="options" name="options[]" value="c">Avec Hebergement
                    <input type="checkbox" id="options" name="options[]" value="d">A Thème
                </div>
                <br><br>
                <label for="etapes"><strong>Étapes :</strong></label>
                <div class="checkbox-groupe">
                    <input type="checkbox" id="etapes2" name="etapes[]" value="2">2
                    <input type="checkbox" id="etapes3" name="etapes[]" value="3">3
                    <input type="checkbox" id="etapes4" name="etapes[]" value="4">4
                    <input type="checkbox" id="etapes5" name="etapes[]" value="5">5
                </div>
                <button type="submit">Rechercher</button>
            </form>
        </div>

    <?php
        require('requires/footer.php');
    ?>
</body>
</html>
