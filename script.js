
function setCookie(name, value, days) {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    expires = "; expires=" + date.toUTCString();
  }
  document.cookie = name + "=" + (value || "") + expires + "; path=/";
}


function getCookie(name) {
  const nameEQ = name + "=";
  const ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}


const savedTheme = getCookie("theme");
if (savedTheme === "dark-mode") {
  document.body.classList.add("dark-mode");
} else {
  document.body.classList.add("light-mode"); // thème par défaut
}


const toggle = document.getElementById("theme-toggle");
toggle.addEventListener("click", () => {
  document.body.classList.toggle("dark-mode");
  document.body.classList.toggle("light-mode");

  if (document.body.classList.contains("dark-mode")) {
    setCookie("theme", "dark-mode", 30);
  } else {
    setCookie("theme", "light-mode", 30);
  }
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
