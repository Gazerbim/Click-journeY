<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="contact-page light-mode">
<?php
    session_start();
    require('requires/header.php');
    afficher_header('contact');
  ?>
    <section class="recherche">
        <h2>Contactez-nous</h2>
        <p><strong>Remplissez le formulaire ci-dessous pour nous envoyer un message.</strong></p>
        <form>
            <label for="nom"><strong>Nom :</strong></label>
            <input type="text" id="nom" name="nom" required>
            
            <label for="email"><strong>Email :</strong></label>
            <input type="email" id="email" name="email" required>
            
            <label for="message"><strong>Message :</strong></label>
            <textarea id="message" name="message" rows="5" required></textarea>
            
            <button>Envoyer</button>
        </form>
    </section>
    <?php
        require('requires/footer.php');
    ?>
    <script src="script.js"></script>
</body>
</html>
