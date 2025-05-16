document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.option-checkbox');
    const voyageId = document.getElementById('voyage-id').value;

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Collecter toutes les options cochées
            const selectedOptions = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            // Créer les données du formulaire
            const formData = new FormData();
            formData.append('voyageId', voyageId);
            formData.append('options', JSON.stringify(selectedOptions));

            // Envoyer la requête AJAX
            fetch('ajax_update_options.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'affichage des prix
                    document.getElementById('prix-base').textContent = data.prixBase + ' €';
                    document.getElementById('prix-estime').textContent = data.prixTotal + ' €';
                    document.getElementById('montant-total').textContent = data.prixTotal + ' €';
                    document.getElementById('montant-form').value = data.prixTotal;

                    // Mettre à jour le récapitulatif des options
                    const recap = data.options
                        .map(opt => `${opt.nom} (+${opt.prix} €)`)
                        .join(', ');
                    document.getElementById('recap-options').textContent = recap;
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    });
});