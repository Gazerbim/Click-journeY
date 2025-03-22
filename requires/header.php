<?php
function afficher_header($page_active) {
    ?>
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
        <div class="header">
            <nav>
                <a class="crous" href="https://www.crous-paris.fr/">
                    <button><img src='images/Krous.png'></button>
                </a>
                <div class="nav-spacer"></div>
                <div class="nav-center">
                    <h1 class="nav-titre">Rush&Krous</h1>
                </div>
                <div class="nav-liens">
                    <a href="index.php" class="<?= ($page_active == 'index') ? 'selected' : '' ?>">
                        <button>Accueil</button>
                    </a>
                    <a href="presentation.php" class="<?= ($page_active == 'presentation') ? 'selected' : '' ?>">
                        <button>Présentation</button>
                    </a>
                    <a href="recherche.php" class="<?= ($page_active == 'recherche') ? 'selected' : '' ?>">
                        <button>Recherche</button>
                    </a>
                    <?php
                        if (isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])) {
                            echo "<a href='deconnexion.php'><button>Déconnexion</button></a>";
                        } else {
                            echo "<a href='connexion.php' class='" . ($page_active == 'connexion' ? 'selected' : '') . "'><button>Connexion</button></a>";
                        }
                    ?>
                    <a href="profil.php" class="<?= ($page_active == 'profil') ? 'selected' : '' ?>">
                        <button>
                        <?php
                            if (isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])) {
                                echo $_SESSION['prenom'];
                            } else {
                                echo "Profil";
                            }
                        ?>
                        </button>
                    </a>
                </div>
            </nav>
        </div>
    <?php
}
afficher_header('profil');
?>
