<?php
    session_start();
    require('requires/header.php');
    afficher_header('presentation');

    function afficherVoyage($value){
        echo "<div class='voyage'>";
        echo "<img src='databases/voyages/" . $value['id'] . "/img/profil.jpg' alt='Voyage " . $value['id'] . "' width='100%' height='60%'>";
        echo "<p>" . htmlspecialchars($value['nom']) . "</p>";
        echo "<p>" . htmlspecialchars($value['description']) . "</p>";
        echo "<p>" . htmlspecialchars($value['tarif']) . "€</p>";
        echo "<a href='voyage_details.php?id=" . urlencode($value["id"]) . "'><button>Détails</button></a>";
        echo "</div>";
    }

    require_once 'requires/json_utilities.php';
    $voyages = recupererVoyages();

    // Gestion des mots-clés
    if(isset($_GET['mot_clef']) && !empty($_GET['mot_clef'])){
        $mots_clef = $_GET['mot_clef'];
        $m = $_GET['mot_clef'];
        $mots_clef = explode(" ", $mots_clef);
    }
    else{
        $mots_clef = [];
        $m = "";
    }

    // Filtrage
    $voyagesFiltres = [];
    foreach ($voyages as $value) {
        $resultat = true;
        $mots = recupererMotsClefVoyage($value['id']);
        foreach($mots_clef as $mot_clef){
            $resultat = $resultat && in_array(strtolower($mot_clef), $mots);
        }
        if($resultat || $mots_clef == []){
            $voyagesFiltres[] = $value;
        }
    }

    // Pagination
    $voyagesParPage = 5; 
    $pageActuelle = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $totalVoyages = count($voyagesFiltres);
    $totalPages = max(1, ceil($totalVoyages / $voyagesParPage));
    $indexDepart = ($pageActuelle - 1) * $voyagesParPage;
    $voyagesAffiches = array_slice($voyagesFiltres, $indexDepart, $voyagesParPage);

    echo "<div class='espaceur'>";
    echo "<div class='liste_voyages'>";
    echo "<h2>Liste des voyages</h2>";
    echo "<div class='voyages-container'>";

    foreach ($voyagesAffiches as $value) {
        afficherVoyage($value);
    }
    echo "</div>";

    // Affichage des boutons
    echo "<div class='pagination'>";
    if ($pageActuelle > 1) {
        echo "<a href='?mot_clef=$m&page=" . ($pageActuelle - 1) . "'>Précédent</a> ";
    }
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?mot_clef=$m&page=$i'" . ($i == $pageActuelle ? " class='active'" : "") . ">$i</a> ";
    }
    if ($pageActuelle < $totalPages) {
        echo "<a href='?mot_clef=$m&page=" . ($pageActuelle + 1) . "'>Suivant</a>";
    }
    echo "</div>";
    echo "</div>";
?>
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
    require('requires/footer.php');
?>
