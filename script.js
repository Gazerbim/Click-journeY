
const toggle = document.getElementById("theme-toggle");
  toggle.addEventListener("click", () => {
    document.body.classList.toggle("dark-mode");
    document.body.classList.toggle("light-mode");
  });

document.addEventListener('DOMContentLoaded', () => {
    const champsMotDePasse = document.querySelectorAll('input[type="password"]');

    champsMotDePasse.forEach(champ => {
        
        const conteneur = document.createElement('div');
        conteneur.classList.add('champ-mdp');

        
        champ.parentNode.insertBefore(conteneur, champ);
        conteneur.appendChild(champ);

        
        const bouton = document.createElement('button');
        bouton.type = 'button';
        bouton.classList.add('bouton-oeil');
        bouton.innerHTML = '👁️';

        conteneur.appendChild(bouton);

        bouton.addEventListener('click', (evenement) => {
            evenement.preventDefault();
            const estMotDePasse = champ.type === 'password';
            champ.type = estMotDePasse ? 'text' : 'password';
            bouton.innerHTML = estMotDePasse ? '🙈' : '👁️';
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
            const actions = document.querySelectorAll('form[action="admin.php"]');

            actions.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const bouton = this.querySelector('button');

                    const suppr = this.querySelector('input[name="delete_id"]');
                    const promote = this.querySelector('input[name="promote_id"]');

                    bouton.disabled = true;
                    bouton.style.opacity = 0.7;
                    bouton.style.cursor = 'wait';

                    if (suppr) {
                        bouton.textContent = 'Suppression en cours...';
                    } else if (promote) {
                        bouton.textContent = 'Modification en cours...';
                    }

                    setTimeout(() => {
                        this.submit();
                    }, 2000);
                });
            });
        })
