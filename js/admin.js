function getUserDetails(id, bouton) {
    // Empêche d'insérer deux fois les détails
    const ligne = bouton.closest("tr");
    if (ligne.nextElementSibling?.classList.contains("user-details-row")) {
        ligne.nextElementSibling.remove();
        return;
    }

    fetch(`admin_fetch.php?id=${id}`)
        .then(response => response.text())
        .then(data => {
            const nouvelleLigne = document.createElement("tr");
            nouvelleLigne.classList.add("user-details-row");
            const cellule = document.createElement("td");
            cellule.colSpan = 6; // Le nombre de colonnes de ton tableau
            cellule.innerHTML = data;
            nouvelleLigne.appendChild(cellule);
            ligne.parentNode.insertBefore(nouvelleLigne, ligne.nextSibling);
        })
        .catch(error => {
            console.error("Erreur:", error);
        });
}
