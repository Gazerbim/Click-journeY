document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll(".option-checkbox");
    const prixBase = parseFloat(document.body.innerHTML.match(/Prix de base\s*:\s*(\d+(?:\.\d+)?)/)[1]);
    const prixEstimeEl = document.getElementById("prix-estime");

    function updatePrixEstime() {
        let total = prixBase;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                const prix = parseFloat(cb.dataset.price);
                if (!isNaN(prix)) {
                    total += prix;
                }
            }
        });

        prixEstimeEl.textContent = total.toFixed(2) + " €";
    }

    checkboxes.forEach(cb => {
        cb.addEventListener("change", updatePrixEstime);
    });

    updatePrixEstime();
});
