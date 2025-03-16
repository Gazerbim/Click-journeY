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
session_start();
require("requires/json_utilities.php");
$tab = lireFichierJson("./databases/users.json");
const ligneParPage = 20;
?>
    <div class="image_header">
        <nav>
            <a class="crous" href="https://www.crous-paris.fr/">
                <button><img src='images/Krous.png'></button>
            </a>
            <div class="nav-spacer"></div>
            <div class="nav-center">
                <h1 class="nav-titre">Rush&Krous</h1>
            </div>
            <div class="nav-liens">
                <a href="index.php"><button>Accueil</button></a>
                <a href="presentation.php"><button>Présentation</button></a>
                <a href="recherche.php"><button>Recherche</button></a>
                <a href="connexion.php"><button>Connexion</button></a>
                <a href="profil.php"><button>
		    <?php
        	
        		if (isset($_SESSION['prenom']) && !empty($_SESSION['prenom'])) {
            			echo $_SESSION['prenom'];
        		} 
			    else {
            			echo "Profil";
        		}
        	?>
		</button></a>
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
                        $page = $_GET['page'];
                        
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
                                echo "<form action='profil.php' method='get'>";
                                echo "<input type='hidden' name='id' value='" . $line['id'] . "'>";
                                echo "</form>";
                                echo "<button type='submit'>Modifier</button>";
                                echo "<button>Supprimer</button>";
                                //echo "<button>Ajouter Reduction</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }else{
                            for ($i = $page*ligneParPage; $i <= min($page*ligneParPage+10, count($tab)-1); $i++) {
                                $line = $tab[$i];
                                echo "<tr>";
                                echo "<td>" . $line['id'] . "</td>";
                                echo "<td>" . $line['nom'] . "</td>";
                                echo "<td>" . $line['prenom'] . "</td>";
                                echo "<td>" . $line['courriel'] . "</td>";
                                echo "<td>" . $line['role'] . "</td>";
                                echo "<td>";
                                echo "<form action='profil.php' method='get'>";
                                echo "<input type='hidden' name='id' value='" . $line['id'] . "'>";
                                echo "<button type='submit'>Modifier</button>";
                                echo "<button>Supprimer</button>";
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
                    for ($i = 0; $i < $nbPages; $i++) {
                        echo "<a href='admin.php?page=$i'>" . ($i+1) . "</a><a> </a>";
                    }
                    echo "<br>";
                    $pageM = $page-1;
                    $pageP = $page+1;
                    if ($pageM < 0) {
                        $pageM = 0;
                    }
                    if ($pageP >= $nbPages) {
                        $pageP = $nbPages-1;
                    }
                    echo "<a href='admin.php?page=$pageM'>"."<"."</a>";
                    echo "<a href='admin.php?page=$pageP'>".">"."</a>";
                }
            ?>
        </div>
        </div>
        
    </main>
</body>
</html>
