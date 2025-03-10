<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Gestion Admin</title>
</head>
<body class="rech">
<?php
require("requires/json_utilities.php");
$tab = lireFichierJson("./databases/users.json");
?>
    <nav>
        <a class="crous" href="https://www.crous-paris.fr/">
            <button><img src="images/Krous.png" alt="Crous"></button>
        </a>
        <div class="nav-spacer"></div>
        <div class="nav-center">
            <h1 class="nav-titre">Rush&Krous</h1>
        </div>
        <div class="nav-liens">
            <a href="index.html"><button>Accueil</button></a>
            <a href="presentation.html"><button>Présentation</button></a>
            <a href="recherche.html"><button>Recherche</button></a>
            <a href="connexion.php"><button>Connexion</button></a>
            <a href="profil.html"><button>Profil</button></a>
        </div>
    </nav>

    <main>
        <div class="recherche admin">
            <h2>Gestion des utilisateurs</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        echo "<tr>";
                        foreach ($tab as $line) {
                            echo "<td>" . $line['id'] . "</td>";
                            echo "<td>" . $line['nom'] . "</td>";
                            echo "<td>" . $line['prenom'] . "</td>";
                            echo "<td>" . $line['courriel'] . "</td>";
                            echo "<td>" . $line['role'] . "</td>";
                            echo "<td>";
                            echo "<button>Modifier</button>";
                            echo "<button>Supprimer</button>";
                            echo "<button>Ajouter Reduction</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
