function modificationProfil() {
    const form = document.querySelector('.formulaire-classique');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submission prevented');
        });
    }

    console.log('Edit buttons found:', document.querySelectorAll('.edit-btn').length);

    const vOrigine = {};
    const champsModif = {};
    const inputChamps = document.querySelectorAll('.formulaire-classique input');

    const statusMessage = document.createElement('div');
    statusMessage.className = 'status-message';
    const formContainer = document.querySelector('.formulaire-classique');
    if (formContainer) {
        formContainer.prepend(statusMessage);
    } else {
        console.error('Form container not found');
    }

    inputChamps.forEach(input => {
        input.disabled = true;
        vOrigine[input.id] = input.value;

        input.addEventListener('input', function() {
            if (this.value !== vOrigine[this.id]) {
                this.classList.add('field-modified');
            } else {
                this.classList.remove('field-modified');
            }
        });
    });

    const submitBouton = document.getElementById('submit-all-changes');
    if (submitBouton) submitBouton.style.display = 'none';

    const modifBoutons = document.querySelectorAll('.edit-btn');
    if (modifBoutons.length === 0) {
        console.error('No edit buttons found. Check your HTML classes.');
    }

    modifBoutons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default action
            console.log('Edit button clicked for:', this.dataset.field);

            const champId = this.dataset.field;
            const inputChamp = document.getElementById(champId);

            if (!inputChamp) {
                console.error('Input field not found:', champId);
                return;
            }

            inputChamp.disabled = false;
            inputChamp.focus();

            this.style.display = 'none';

            const saveBtn = document.querySelector(`.save-btn[data-field="${champId}"]`);
            const cancelBtn = document.querySelector(`.cancel-btn[data-field="${champId}"]`);

            if (saveBtn) saveBtn.style.display = 'inline-block';
            if (cancelBtn) cancelBtn.style.display = 'inline-block';
        });
    });

    const saveBoutons = document.querySelectorAll('.save-btn');
    saveBoutons.forEach(button => {
        button.addEventListener('click', function() {
            const champId = this.dataset.field;
            const inputChamp = document.getElementById(champId);

            if (inputChamp.value !== vOrigine[champId]) {
                champsModif[champId] = inputChamp.value;
                if (submitBouton) submitBouton.style.display = 'block';
            } else {
                inputChamp.classList.remove('field-modified');
            }

            inputChamp.disabled = true;
            this.style.display = 'none';
            document.querySelector(`.cancel-btn[data-field="${champId}"]`).style.display = 'none';
            document.querySelector(`.edit-btn[data-field="${champId}"]`).style.display = 'inline-block';
        });
    });

    const cancelBoutons = document.querySelectorAll('.cancel-btn');
    cancelBoutons.forEach(button => {
        button.addEventListener('click', function() {
            const champId = this.dataset.field;
            const inputChamp = document.getElementById(champId);

            inputChamp.value = vOrigine[champId];
            inputChamp.classList.remove('field-modified');
            inputChamp.disabled = true;

            this.style.display = 'none';
            document.querySelector(`.save-btn[data-field="${champId}"]`).style.display = 'none';
            document.querySelector(`.edit-btn[data-field="${champId}"]`).style.display = 'inline-block';
        });
    });

    const profileForm = document.getElementById('profile-form');
    if (profileForm && submitBouton) {
        submitBouton.addEventListener('click', function(e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('action', 'update_profil');

            for (const champId in champsModif) {
                formData.append(champId, champsModif[champId]);
            }

            statusMessage.textContent = "Mise à jour en cours...";
            statusMessage.className = 'status-message loading';

            fetch('modification_profil_ajax.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        statusMessage.textContent = data.message;
                        statusMessage.className = 'status-message success';

                        if (data.updates) {
                            for (const field in data.updates) {
                                const inputElement = document.getElementById(field);
                                if (inputElement) {
                                    vOrigine[field] = data.updates[field];
                                }
                            }
                        }

                        Object.keys(champsModif).forEach(key => {
                            delete champsModif[key];
                        });
                        submitBouton.style.display = 'none';

                        document.querySelectorAll('.field-modified').forEach(el => {
                            el.classList.remove('field-modified');
                        });

                        document.getElementById('confirm-password-field').style.display = 'none';
                        document.getElementById('mdp').value = '';
                        document.getElementById('cmdp').value = '';
                    } else {
                        statusMessage.textContent = data.message || "Une erreur est survenue.";
                        statusMessage.className = 'status-message error';

                        for (const champId in champsModif) {
                            const inputElement = document.getElementById(champId);
                            if (inputElement) {
                                inputElement.value = vOrigine[champId];
                                inputElement.classList.remove('field-modified');
                            }
                        }
                    }

                    setTimeout(() => {
                        statusMessage.textContent = '';
                        statusMessage.className = 'status-message';
                    }, 5000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusMessage.textContent = "Une erreur réseau s'est produite.";
                    statusMessage.className = 'status-message error';

                    for (const champId in champsModif) {
                        const inputElement = document.getElementById(champId);
                        if (inputElement) {
                            inputElement.value = vOrigine[champId];
                            inputElement.classList.remove('field-modified');
                        }
                    }
                });
        });
    }

    const mdpModifBtn = document.querySelector('.edit-btn[data-field="mdp"]');
    if (mdpModifBtn) {
        mdpModifBtn.addEventListener('click', function() {
            document.getElementById('confirm-password-field').style.display = 'block';
        });
    }

    const mdpCancelBtn = document.querySelector('.cancel-btn[data-field="mdp"]');
    if (mdpCancelBtn) {
        mdpCancelBtn.addEventListener('click', function() {
            document.getElementById('confirm-password-field').style.display = 'none';
            document.getElementById('cmdp').value = '';
        });
    }

    const mdpSaveBtn = document.querySelector('.save-btn[data-field="mdp"]');
    if (mdpSaveBtn) {
        mdpSaveBtn.addEventListener('click', function() {
            const mdp = document.getElementById('mdp').value;
            const cMdp = document.getElementById('cmdp').value;

            if (mdp !== cMdp) {
                statusMessage.textContent = 'Les mots de passe ne correspondent pas.';
                statusMessage.className = 'status-message error';
                setTimeout(() => {
                    statusMessage.textContent = '';
                    statusMessage.className = 'status-message';
                }, 5000);
                return;
            }

            if (mdp) {
                champsModif['mdp'] = mdp;
                champsModif['cmdp'] = cMdp;
                if (submitBouton) submitBouton.style.display = 'block';
            }
        });
    }
}
document.addEventListener('DOMContentLoaded', function() {
    modificationProfil();
    console.log('Profile modification initialized');
});