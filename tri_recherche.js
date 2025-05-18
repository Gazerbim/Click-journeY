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