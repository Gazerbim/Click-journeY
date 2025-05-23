<?php
function afficher_header($page_active) {
    ?>
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
	    <button class="yang" id="theme-toggle"><img src="images/clair.png" alt="Changer de thème"></button>
        </nav>
    </div>
    <?php
}
?>
