<?php
require("requires/json_utilities.php");
$users = lireFichierJson("./databases/users.json");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $nb_voyages_panier = recupererNombreVoyagesPanier($id);
    $nb_voyages_reserves = recupererNombreVoyageUtilisateur($id);
    $noms_voyages = recupererNomsVoyagesUtilisateur($id);
    foreach ($users as $user) {
        if ($user['id'] == $id) {
            // Tu peux personnaliser ce tableau avec ce que tu veux afficher
            echo "<div class='espaceur'></div>";
            echo "<table class='details-table'>";
            echo "<tr><td><strong>Nombre de voyages dans le panier:</strong></td><td>{$nb_voyages_panier}</td></tr>";
            echo "<tr><td><strong>Nombre de voyages réservés:</strong></td><td>{$nb_voyages_reserves}</td></tr>";
            if($nb_voyages_reserves > 0){
                echo "<tr><td><strong>Voyages réservés:</strong></td><td>";
                foreach ($noms_voyages as $nom_voyage) {
                    echo htmlspecialchars($nom_voyage) . "<br>";
                }
                echo "</td></tr>";
            }
            echo "<tr><td><strong>Téléphone:</strong></td><td>{$user['tel']}</td></tr>";
            echo "<tr><td><strong>Date de Naissance:</strong></td><td>{$user['naissance']}</td></tr>";
            echo "<tr><td><strong>Genre:</strong></td><td>{$user['genre']}</td></tr>";
            echo "</table>";
            echo "<div class='espaceur'></div>";
            exit;
        }
    }
}
http_response_code(404);
echo "Utilisateur non trouvé.";
