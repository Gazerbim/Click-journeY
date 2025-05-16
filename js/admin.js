function getUserDetails(id, bouton) {
    // Empêche d'insérer deux fois les détails
    const ligne = bouton.closest("tr");
    document.body.style.cursor = "wait";
    bouton.style.cursor = "wait";
    if (ligne.nextElementSibling?.classList.contains("user-details-row")) {
        ligne.nextElementSibling.remove();
        bouton.style.cursor = "default";
        document.body.style.cursor = "default";
        return;
    }

    fetch(`admin_fetch.php?id=${id}&action=details`)
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
        })
        .finally(() => {
            // Remet le curseur par défaut
            bouton.style.cursor = "default";
            document.body.style.cursor = "default";
        });
}

function supprimerUtilisateur(id, bouton) {
    // Empêche d'insérer deux fois les détails
    const ligne = bouton.closest("tr");
    document.body.style.cursor = "wait";
    bouton.style.cursor = "wait";

    fetch(`admin_fetch.php?id=${id}&action=supprimer`)
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                // Supprime la ligne de l'utilisateur
                ligne.remove();
            } else {
                alert("Erreur lors de la suppression de l'utilisateur.");
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
        })
        .finally(() => {
            // Remet le curseur par défaut
            bouton.style.cursor = "default";
            document.body.style.cursor = "default";
        });
}

function promouvoirUtilisateur(id, bouton) {
    const ligne = bouton.closest("tr");
    const roleCell = ligne.querySelector(".role-cell");

    if (!roleCell) {
        console.error("Cellule de rôle non trouvée");
        return;
    }

    document.body.style.cursor = "wait";
    bouton.style.cursor = "wait";

    fetch(`admin_fetch.php?id=${id}&action=promouvoir`)
        .then(async response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            if (data === "success") {
                const currentRole = roleCell.textContent.trim().toLowerCase();
                if (currentRole === "user") {
                    roleCell.textContent = "adm";
                    bouton.textContent = "Rétrograder en User";
                } else {
                    roleCell.textContent = "user";
                    bouton.textContent = "Promouvoir en Admin";
                }
            } else {
                throw new Error(data || "Erreur lors de la promotion");
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
            alert("Erreur lors de la modification du rôle: " + error.message);
        })
        .finally(() => {
            bouton.style.cursor = "default";
            document.body.style.cursor = "default";
        });
}