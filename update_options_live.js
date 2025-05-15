document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll(".option-checkbox");
    const voyageId = document.getElementById("voyage-id").value;

    checkboxes.forEach(cb => {
        cb.addEventListener("change", () => {
            const selected = Array.from(checkboxes)
                .filter(c => c.checked)
                .map(c => c.value);

            const formData = new FormData();
            formData.append("voyageId", voyageId);
            selected.forEach(opt => formData.append("options[]", opt));

            // 1. Mettre à jour prix & résumé
            fetch("ajax_update_options.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) throw new Error(data.message);

                document.getElementById("prix-estime").textContent = data.prixTotal + " €";
                document.getElementById("prix-base").textContent = data.prixBase + " €";
                document.getElementById("montant-total").textContent = data.prixTotal + " €";
                document.getElementById("montant-form").value = data.prixTotal;

                const recap = document.getElementById("recap-options");
                recap.innerHTML = data.options.length
                    ? data.options.map(o => `${o.nom} (+${o.prix} €)`).join(', ')
                    : "Aucune option sélectionnée";

                // 2. Mettre à jour la valeur de contrôle
                const controlData = new FormData();
                controlData.append("voyageId", voyageId);
                selected.forEach(opt => controlData.append("options[]", opt));

                return fetch("ajax_update_control.php", {
                    method: "POST",
                    body: controlData
                });
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("control-input").value = data.control;
                } else {
                    console.error("Erreur mise à jour control :", data.message);
                }
            })
            .catch(err => console.error("Erreur AJAX :", err));
        });
    });
});
