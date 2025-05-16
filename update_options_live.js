document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll(".option-checkbox");
    const voyageId = document.getElementById("voyage-id").value;

    checkboxes.forEach(cb => {
        cb.addEventListener("change", async () => {
            const selected = Array.from(checkboxes)
                .filter(c => c.checked)
                .map(c => c.value);

            const formData = new FormData();
            formData.append("voyageId", voyageId);
            formData.append("options", JSON.stringify(selected));

            // Premier try/catch pour la mise à jour des prix
            try {
                const priceResponse = await fetch("ajax_update_options.php", {
                    method: "POST",
                    body: formData
                });
                const priceData = await priceResponse.json();

                if (!priceData.success) throw new Error(priceData.message);

                // Mise à jour des éléments d'interface
                document.getElementById("prix-estime").textContent = priceData.prixTotal + " €";
                document.getElementById("prix-base").textContent = priceData.prixBase + " €";
                document.getElementById("montant-total").textContent = priceData.prixTotal + " €";
                document.getElementById("montant-form").value = priceData.prixTotal;

                const recap = document.getElementById("recap-options");
                recap.textContent = priceData.options
                    .map(opt => `${opt.nom} (+${opt.prix} €)`)
                    .join(', ');
            } catch (error) {
                console.error("Erreur mise à jour prix:", error);
                return; // Arrêter l'exécution si la première requête échoue
            }

            // Deuxième try/catch pour la mise à jour du control
            try {
                const controlResponse = await fetch("ajax_update_control.php", {
                    method: "POST",
                    body: formData
                });
                const controlData = await controlResponse.json();

                if (!controlData.success) throw new Error(controlData.message);

                document.getElementById("control-input").value = controlData.control;
            } catch (error) {
                console.error("Erreur mise à jour control:", error);
            }
        });
    });
});