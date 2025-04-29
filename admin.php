<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Gestion Admin</title>
</head>
<body class="rech light-mode">
<?php
session_start();
require("requires/json_utilities.php");
$tab = lireFichierJson("./databases/users.json");
const ligneParPage = 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];
        supprimerUtilisateur($deleteId);
        header('Location: admin.php');
    } elseif (isset($_POST['promote_id'])) {
        $promoteId = $_POST['promote_id'];
        foreach ($tab as &$user) {
            if ($user['id'] == $promoteId) {
                if ($user['role'] == 'user') {
                    modifierRoleUtilisateur($user['id'], 'adm');
                } else {
                    modifierRoleUtilisateur($user['id'], 'user');
                }
                break;
            }
        }
        header('Location: admin.php');
    }
}

require('requires/header.php');
afficher_header('admin');
?>
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
                        if ($_SESSION['role'] != "adm") {
                            header('Location: index.php');
                        }

                        if(isset($_GET['page'])){
                            $page = $_GET['page'];
                        }else{
                            $page = 0;
                        }
                        
                        if ($page == NULL) {
                            $page = 0;
                        }
                        if (count($tab) <= ligneParPage) {
                            foreach ($tab as $line) {
                                echo "<tr>";
                                echo "<td>" . $line['id'] . "</td>";
                                echo "<td>" . $line['nom'] . "</td>";
                                echo "<td>" . $line['prenom'] . "</td>";
                                echo "<td>" . $line['courriel'] . "</td>";
                                echo "<td>" . $line['role'] . "</td>";
                                echo "<td>";
                                echo "<form action='admin.php' method='post' style='display:inline;'>";
                                echo "<input type='hidden' name='promote_id' value='" . $line['id'] . "'>";
                                if ($line['role'] == 'user') {
                                    echo "<button type='submit'>Mettre Admin</button>";
                                } else {
                                    echo "<button type='submit'>Rendre User</button>";
                                }
                                echo "</form>";
                                echo "<form action='admin.php' method='post' style='display:inline'>";
                                echo "<input type='hidden' name='delete_id' value='" . $line['id'] . "'>";
                                echo "<button type='submit'>Supprimer</button>";
                                echo "</form>";
                                //echo "<button>Ajouter Reduction</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }else{
                            for ($i = $page*ligneParPage; $i < min(($page+1)*ligneParPage, count($tab)); $i++) {
                                $line = $tab[$i];
                                echo "<tr>";
                                echo "<td>" . $line['id'] . "</td>";
                                echo "<td>" . $line['nom'] . "</td>";
                                echo "<td>" . $line['prenom'] . "</td>";
                                echo "<td>" . $line['courriel'] . "</td>";
                                echo "<td>" . $line['role'] . "</td>";
                                echo "<td>";
                                echo "<form action='admin.php' method='post' style='display:inline;'>";
                                echo "<input type='hidden' name='promote_id' value='" . $line['id'] . "'>";
                                if ($line['role'] == 'user') {
                                    echo "<button type='submit'>Promouvoir en Admin</button>";
                                } else {
                                    echo "<button type='submit'>Rétrograder en User</button>";
                                }
                                echo "</form>";
                                echo "<form action='admin.php' method='post' style='display:inline;'>";
                                echo "<input type='hidden' name='delete_id' value='" . $line['id'] . "'>";
                                echo "<button type='submit'>Supprimer</button>";
                                echo "</form>";
                                //echo "<button>Ajouter Reduction</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
            <div class="pagination">
            <?php
                $nbPages = ceil(count($tab)/ligneParPage);
                if($nbPages != 1){
                    $pageM = $page-1;
                    if ($pageM < 0) {
                        $pageM = 0;
                    }
                    echo "<a href='admin.php?page=$pageM'>"."<"."</a>";
                    for ($i = 0; $i < $nbPages; $i++) {
                        if ($i == $page) {
                            echo "<a class='active'>" . ($i+1) . "</a>";
                            continue;
                        }
                        echo "<a href='admin.php?page=$i'>" . ($i+1) . "</a>";
                    }
                    echo "<br>";
                    
                    $pageP = $page+1;
                    if ($pageP >= $nbPages) {
                        $pageP = $nbPages-1;
                    }
                    
                    echo "<a href='admin.php?page=$pageP'>".">"."</a>";
                }
            ?>
        </div>
        </div>
        
    </main>
    <script src="script.js"></script>
</body>
</html>
