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

        const itemsParPage = 6;
        const voyagesContainer = document.querySelector('.voyages-container');
        const triSelect = document.getElementById('tri');
        let pageActu = 1;

        function creationPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsParPage);

            const existPagination = document.querySelector('.pagination');
            if (existPagination) {
                existPagination.remove();
            }

            if (totalPages <= 1) {
                return;
            }

            const paginationContainer = document.createElement('div');
            paginationContainer.className = 'pagination';

            const prevBtn = document.createElement('a');
            prevBtn.href = 'javascript:void(0)';
            prevBtn.textContent = 'Précédent';
            if (pageActu === 1) {
                prevBtn.style.opacity = '0.5';
                prevBtn.style.pointerEvents = 'none';
            }
            prevBtn.addEventListener('click', () => {
                if (pageActu > 1) {
                    changerPage(pageActu - 1);
                }
            });
            paginationContainer.appendChild(prevBtn);

            const displayedPages = getPageAffiche(pageActu, totalPages);
            displayedPages.forEach(page => {
                if (page === '...') {
                    const ellipse = document.createElement('span');
                    ellipse.style.padding = '10px 15px';
                    ellipse.style.color = 'var(--p2-color)';
                    ellipse.textContent = '...';
                    paginationContainer.appendChild(ellipse);
                } else {
                    const pageBtn = document.createElement('a');
                    pageBtn.href = 'javascript:void(0)';
                    if (page === pageActu) {
                        pageBtn.classList.add('active');
                    }
                    pageBtn.textContent = page;
                    pageBtn.addEventListener('click', () => changerPage(page));
                    paginationContainer.appendChild(pageBtn);
                }
            });

            const nextBtn = document.createElement('a');
            nextBtn.href = 'javascript:void(0)';
            nextBtn.textContent = 'Suivant';
            if (pageActu === totalPages) {
                nextBtn.style.opacity = '0.5';
                nextBtn.style.pointerEvents = 'none';
            }
            nextBtn.addEventListener('click', () => {
                if (pageActu < totalPages) {
                    changerPage(pageActu + 1);
                }
            });
            paginationContainer.appendChild(nextBtn);

            voyagesContainer.parentNode.insertBefore(paginationContainer, voyagesContainer.nextSibling);
        }

        function getPageAffiche(actu, total) {
            if (total <= 7) {
                return Array.from({length: total}, (_, i) => i + 1);
            } else {
                const pages = [];

                pages.push(1);

                if (actu > 3) {
                    pages.push('...');
                }

                const rangeDebut = Math.max(2, actu - 1);
                const rangeFin = Math.min(total - 1, actu + 1);

                for (let i = rangeDebut; i <= rangeFin; i++) {
                    pages.push(i);
                }

                if (actu < total - 2) {
                    pages.push('...');
                }

                pages.push(total);

                return pages;
            }
        }

        function changerPage(nvlPage) {
            pageActu = nvlPage;
            const voyages = document.querySelectorAll('.voyage');

            voyages.forEach((voyage, index) => {
                const indexDebut = (pageActu - 1) * itemsParPage;
                const indexFin = indexDebut + itemsParPage;

                if (index >= indexDebut && index < indexFin) {
                    voyage.classList.remove('hidden-voyage');
                } else {
                    voyage.classList.add('hidden-voyage');
                }
            });

            creationPagination(voyages.length);

            voyagesContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        triSelect.addEventListener('change', function() {
            const trierPar = this.value;
            const voyages = Array.from(document.querySelectorAll('.voyage'));

            voyages.sort((a, b) => {
                switch(trierPar) {
                    case 'prix-c': return extrairePrice(a) - extrairePrice(b);
                    case 'prix-dc': return extrairePrice(b) - extrairePrice(a);
                    case 'nom-c': return extraireTexte(a).localeCompare(extraireTexte(b));
                    case 'nom-dc': return extraireTexte(b).localeCompare(extraireTexte(a));
                    case 'date-c':
                        const dateStrA1 = a.dataset.date;
                        const dateStrB1 = b.dataset.date;

                        const partsA1 = dateStrA1.split('/');
                        const partsB1 = dateStrB1.split('/');

                        const sortableDateA1 = `${partsA1[2]}/${partsA1[1]}/${partsA1[0]}`;
                        const sortableDateB1 = `${partsB1[2]}/${partsB1[1]}/${partsB1[0]}`;

                        console.log(`Comparing: ${dateStrA1} vs ${dateStrB1} → ${sortableDateA1} vs ${sortableDateB1}`);

                        return sortableDateA1.localeCompare(sortableDateB1);

                    case 'date-dc':
                        const dateStrA2 = a.dataset.date;
                        const dateStrB2 = b.dataset.date;

                        const partsA2 = dateStrA2.split('/');
                        const partsB2 = dateStrB2.split('/');

                        const sortableDateA2 = `${partsA2[2]}/${partsA2[1]}/${partsA2[0]}`;
                        const sortableDateB2 = `${partsB2[2]}/${partsB2[1]}/${partsB2[0]}`;

                        console.log(`Comparing (DC): ${dateStrA2} vs ${dateStrB2} → ${sortableDateA2} vs ${sortableDateB2}`);

                        return sortableDateB2.localeCompare(sortableDateA2);
                    case 'etapes-c': return parseInt(a.dataset.etapes) - parseInt(b.dataset.etapes);
                    case 'etapes-dc': return parseInt(b.dataset.etapes) - parseInt(a.dataset.etapes);
                    default: return 0;
                }
            });

            voyagesContainer.innerHTML = '';
            voyages.forEach(voyage => voyagesContainer.appendChild(voyage));

            pageActu = 1;
            changerPage(1);
        });

        function extrairePrice(voyageElement) {
            const prixTexte = voyageElement.querySelector('p:nth-of-type(3)').textContent;
            return parseFloat(prixTexte.replace('€', '').trim());
        }

        function extraireTexte(voyageElement) {
            return voyageElement.querySelector('p:nth-of-type(1)').textContent.trim();
        }

        const allVoyages = document.querySelectorAll('.voyage');
        allVoyages.forEach((voyage, index) => {
            if (index >= itemsParPage) {
                voyage.classList.add('hidden-voyage');
            }
        });

        creationPagination(allVoyages.length);

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
