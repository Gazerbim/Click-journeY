<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Recherche</title>
</head>
<body class="rech light-mode" >
<?php
session_start();
require("requires/json_utilities.php");
include('requires/header.php');
afficher_header('recherche');
?>
    <div class="recherche">
        <h2>Recherche de voyages</h2>
        <form action="resultat_recherche.php" method="GET">
            <label for="lieu"><strong>Crous à rechercher :</strong></label>
            <input type="text" id="lieu" name="lieu" placeholder="Entrez un krous que vous voulez visiter...">
            <br><br>
            <label for="date_debut"><strong>Période de voyage souhaitée :</strong></label>
            <div class="date-range" style="display: flex; align-items: center; gap: 20px; margin-top: 20px">
                <label for="date_debut">Du</label>
                <input type="date" id="date_debut" name="date_debut"
                       value="<?php echo isset($_GET['date_debut']) ? htmlspecialchars($_GET['date_debut']) : ''; ?>">
                <label for="date_fin">Au</label>
                <input type="date" id="date_fin" name="date_fin"
                       value="<?php echo isset($_GET['date_fin']) ? htmlspecialchars($_GET['date_fin']) : ''; ?>">
            </div>
            <br><br>
            <label for="prix"><strong>Prix :</strong></label>
            <div class="prix-range">
                <input type="number" id="prix_min" name="prix_min" min="0" placeholder="Prix minimum"
                       value="<?php echo isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : ''; ?>">
                <span>à</span>
                <input type="number" id="prix_max" name="prix_max" min="0" placeholder="Prix maximum"
                       value="<?php echo isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : ''; ?>">
                <span>€</span>
            </div>
            <label for="etapes"><strong>Étapes :</strong></label>
            <div class="checkbox-groupe">
                <input type="checkbox" id="etapes" name="etapes[]" value="2">2
                <input type="checkbox" id="etapes" name="etapes[]" value="3">3
                <input type="checkbox" id="etapes" name="etapes[]" value="4">4
                <input type="checkbox" id="etapes" name="etapes[]" value="5">5
            </div>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <div class="liste_voyages">
        <h2>Résultats de la recherche</h2>

        <div class="tri-options">
            <label for="tri"><strong>Trier par :</strong></label>
            <select id="tri">
                <option value="default">Par défaut</option>
                <option value="prix-c">Prix croissant</option>
                <option value="prix-dc">Prix décroissant</option>
                <option value="nom-c">Nom (A-Z)</option>
                <option value="nom-dc">Nom (Z-A)</option>
                <option value="date-c">Date (plus proche)</option>
                <option value="date-dc">Date (plus lointaine)</option>
                <option value="etapes-c">Nombre d'étapes (croissant)</option>
                <option value="etapes-dc">Nombre d'étapes (décroissant)</option>
            </select>
        </div>

        <div class="voyages-container">
        <?php

        function afficherVoyage($value){
            $etapes = count(recupererEtapesVoyage($value['id']));
            $date = avoirDateVoyage($value['id']);

            echo "<div class='voyage' data-etapes='$etapes' data-date='$date'>";
            echo "<img src='databases/voyages/" . $value['id'] . "/img/profil.jpg' alt='Voyage " . $value['id'] . "' width='100%' height='60%'>";
            echo "<p>" . htmlspecialchars($value['nom']) . "</p>";
            echo "<p>" . htmlspecialchars($value['description']) . "</p>";
            echo "<p>" . htmlspecialchars($value['tarif']) . "€</p>";
            echo "<a href='voyage_details.php?id=" . urlencode($value["id"]) . "'><button>Détails</button></a>";
            echo "</div>";
        }



        if(isset($_GET['lieu'])){
            $lieu = $_GET['lieu'];
            $lieu = explode(" ", $lieu);
        }
        else{
            $lieu = [""];
        }
        $date_debut = $_GET['date_debut'] ?? "";
        $date_fin = $_GET['date_fin'] ?? "";
        $villeSelectionnee = $_GET['ville'] ?? "";
        $prix_min = isset($_GET['prix_min']) && is_numeric($_GET['prix_min']) ? (float)$_GET['prix_min'] : null;
        $prix_max = isset($_GET['prix_max']) && is_numeric($_GET['prix_max']) ? (float)$_GET['prix_max'] : null;
        $etapes = $_GET['etapes'] ?? [];
        $voyages = recupererVoyages();
        $voyagesSelectionnes = [];
        foreach ($voyages as $value) {
            $resultat = true;
            $mots = avoirListeMotsVoyage($value['id']);
            $dates = avoirDateVoyage($value['id']);
            foreach($lieu as $mot_clef){
                if (!(strpos(strtolower($mots), strtolower($mot_clef))!==false)){
                    $resultat = false;
                    break;
                }
                if (!empty($etapes)){
                    $etapesMatch = false;
                    foreach($etapes as $etape){
                        if($etape == count(recupererEtapesVoyage($value['id']))){
                            $etapesMatch = true;
                            break;
                        }
                    }
                    if(!$etapesMatch){
                        $resultat = false;
                    }
                }
            }
            if(!empty($villeSelectionnee)) {
                $etapesVoyage = recupererEtapesVoyage($value['id']);
                $villeMatch = false;
                foreach($etapesVoyage as $etape) {
                    if(strtolower($etape['ville']) == strtolower($villeSelectionnee)) {
                        $villeMatch = true;
                        break;
                    }
                }
                if(!$villeMatch) {
                    $resultat = false;
                }
            }
            if($date_debut != "" || $date_fin != ""){
                $dateVoyage = DateTime::createFromFormat('d/m/Y', $dates);
                if($date_debut != ""){
                    $dateDebut = new DateTime($date_debut);
                    if($dateVoyage < $dateDebut){
                        $resultat = false;
                    }
                }
                if($date_fin != ""){
                    $dateFin = new DateTime($date_fin);
                    if($dateVoyage > $dateFin){
                        $resultat = false;
                    }
                }
            }
            if($resultat && $prix_min !== null) {
                $prix = (float)str_replace(',', '.', $value['tarif']);
                if($prix < $prix_min) {
                    $resultat = false;
                }
            }
            if($resultat && $prix_max !== null) {
                $prix = (float)str_replace(',', '.', $value['tarif']);
                if($prix > $prix_max) {
                    $resultat = false;
                }
            }
            if($resultat){
                $voyagesSelectionnes[] = $value;
            }
        }

        foreach ($voyagesSelectionnes as $value) {
            afficherVoyage($value);
        }
        ?>
        </div>
    </div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const itemsPerPage = 6; // Same as voyagesParCharge
        const voyagesContainer = document.querySelector('.voyages-container');
        const triSelect = document.getElementById('tri');
        let currentPage = 1;

        // Function to create pagination controls
        // Function to create pagination controls
        function createPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);

            // Remove existing pagination if any
            const existingPagination = document.querySelector('.pagination');
            if (existingPagination) {
                existingPagination.remove();
            }

            // If only one page or no items, don't show pagination
            if (totalPages <= 1) {
                return;
            }

            // Create pagination container
            const paginationContainer = document.createElement('div');
            paginationContainer.className = 'pagination';

            // Previous button
            const prevBtn = document.createElement('a');
            prevBtn.href = 'javascript:void(0)';
            prevBtn.textContent = 'Précédent';
            if (currentPage === 1) {
                prevBtn.style.opacity = '0.5';
                prevBtn.style.pointerEvents = 'none';
            }
            prevBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    changePage(currentPage - 1);
                }
            });
            paginationContainer.appendChild(prevBtn);

            // Page buttons
            const displayedPages = getDisplayedPages(currentPage, totalPages);
            displayedPages.forEach(page => {
                if (page === '...') {
                    // Ellipsis
                    const ellipsis = document.createElement('span');
                    ellipsis.style.padding = '10px 15px';
                    ellipsis.style.color = 'var(--p2-color)';
                    ellipsis.textContent = '...';
                    paginationContainer.appendChild(ellipsis);
                } else {
                    // Page number
                    const pageBtn = document.createElement('a');
                    pageBtn.href = 'javascript:void(0)';
                    if (page === currentPage) {
                        pageBtn.classList.add('active');
                    }
                    pageBtn.textContent = page;
                    pageBtn.addEventListener('click', () => changePage(page));
                    paginationContainer.appendChild(pageBtn);
                }
            });

            // Next button
            const nextBtn = document.createElement('a');
            nextBtn.href = 'javascript:void(0)';
            nextBtn.textContent = 'Suivant';
            if (currentPage === totalPages) {
                nextBtn.style.opacity = '0.5';
                nextBtn.style.pointerEvents = 'none';
            }
            nextBtn.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    changePage(currentPage + 1);
                }
            });
            paginationContainer.appendChild(nextBtn);

            // Add pagination to the DOM
            voyagesContainer.parentNode.insertBefore(paginationContainer, voyagesContainer.nextSibling);
        }

        // Helper function to determine which page numbers to display
        function getDisplayedPages(current, total) {
            if (total <= 7) {
                // Show all pages if there are 7 or fewer
                return Array.from({length: total}, (_, i) => i + 1);
            } else {
                // More complex logic for many pages
                const pages = [];

                // Always show first page
                pages.push(1);

                // Show dots if not starting at page 2
                if (current > 3) {
                    pages.push('...');
                }

                // Calculate range around current page
                const rangeStart = Math.max(2, current - 1);
                const rangeEnd = Math.min(total - 1, current + 1);

                // Add range of pages
                for (let i = rangeStart; i <= rangeEnd; i++) {
                    pages.push(i);
                }

                // Show dots if not ending at second-to-last page
                if (current < total - 2) {
                    pages.push('...');
                }

                // Always show last page
                pages.push(total);

                return pages;
            }
        }

        // Function to change the current page
        function changePage(newPage) {
            currentPage = newPage;
            const voyages = document.querySelectorAll('.voyage');

            voyages.forEach((voyage, index) => {
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;

                if (index >= startIndex && index < endIndex) {
                    voyage.classList.remove('hidden-voyage');
                } else {
                    voyage.classList.add('hidden-voyage');
                }
            });

            // Update pagination
            createPagination(voyages.length);

            // Scroll to top of results
            voyagesContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Sort functionality - keep existing code but modify the end
        triSelect.addEventListener('change', function() {
            const sortBy = this.value;
            const voyages = Array.from(document.querySelectorAll('.voyage'));

            voyages.sort((a, b) => {
                switch(sortBy) {
                    case 'prix-c': return extractPrice(a) - extractPrice(b);
                    case 'prix-dc': return extractPrice(b) - extractPrice(a);
                    case 'nom-c': return extractText(a).localeCompare(extractText(b));
                    case 'nom-dc': return extractText(b).localeCompare(extractText(a));
                    case 'date-c':
                        // Get dates from dataset
                        const dateStrA1 = a.dataset.date;
                        const dateStrB1 = b.dataset.date;

                        // Convert DD/MM/YYYY to YYYY/MM/DD for proper sorting
                        const partsA1 = dateStrA1.split('/');
                        const partsB1 = dateStrB1.split('/');

                        // Create sortable date strings (YYYY/MM/DD format)
                        const sortableDateA1 = `${partsA1[2]}/${partsA1[1]}/${partsA1[0]}`;
                        const sortableDateB1 = `${partsB1[2]}/${partsB1[1]}/${partsB1[0]}`;

                        console.log(`Comparing: ${dateStrA1} vs ${dateStrB1} → ${sortableDateA1} vs ${sortableDateB1}`);

                        // Simple string comparison works for YYYY/MM/DD format
                        return sortableDateA1.localeCompare(sortableDateB1);

                    case 'date-dc':
                        // Get dates from dataset
                        const dateStrA2 = a.dataset.date;
                        const dateStrB2 = b.dataset.date;

                        // Convert DD/MM/YYYY to YYYY/MM/DD for proper sorting
                        const partsA2 = dateStrA2.split('/');
                        const partsB2 = dateStrB2.split('/');

                        // Create sortable date strings (YYYY/MM/DD format)
                        const sortableDateA2 = `${partsA2[2]}/${partsA2[1]}/${partsA2[0]}`;
                        const sortableDateB2 = `${partsB2[2]}/${partsB2[1]}/${partsB2[0]}`;

                        console.log(`Comparing (DC): ${dateStrA2} vs ${dateStrB2} → ${sortableDateA2} vs ${sortableDateB2}`);

                        // Reverse the comparison for descending order
                        return sortableDateB2.localeCompare(sortableDateA2);
                    case 'etapes-c': return parseInt(a.dataset.etapes) - parseInt(b.dataset.etapes);
                    case 'etapes-dc': return parseInt(b.dataset.etapes) - parseInt(a.dataset.etapes);
                    default: return 0;
                }
            });

            voyagesContainer.innerHTML = '';
            voyages.forEach(voyage => voyagesContainer.appendChild(voyage));

            // Reset to page 1 after sorting
            currentPage = 1;
            changePage(1);
        });

        function extractPrice(voyageElement) {
            const priceText = voyageElement.querySelector('p:nth-of-type(3)').textContent;
            return parseFloat(priceText.replace('€', '').trim());
        }

        function extractText(voyageElement) {
            return voyageElement.querySelector('p:nth-of-type(1)').textContent.trim();
        }

        // Initial setup - hide all voyages except first page
        const allVoyages = document.querySelectorAll('.voyage');
        allVoyages.forEach((voyage, index) => {
            if (index >= itemsPerPage) {
                voyage.classList.add('hidden-voyage');
            }
        });

        // Create initial pagination
        createPagination(allVoyages.length);

        // Remove the old "load more" button if it exists
        const oldLoadMoreBtn = document.getElementById('load-more-btn');
        const oldLoadMoreContainer = document.querySelector('.load-more-container');
        if (oldLoadMoreContainer) {
            oldLoadMoreContainer.remove();
        }
    });
</script>
<script src="script.js"></script>
<?php
require('requires/footer.php');
?>
</body>
</html>
