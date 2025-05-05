<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rush&Krous - Profil utilisateur</title>
</head>
<body class="profil light-mode" >
<?php
    session_start();
    include('requires/header.php');
    afficher_header('profil');
    if (!isset($_SESSION['prenom']) || empty($_SESSION['prenom'])) {
        $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
        header('Location: connexion.php');
        exit();
    }
  ?>
    <div class="recherche">
        <h2>Mon Profil</h2>

        <?php
            
            require("requires/json_utilities.php");
            if (isset($_SESSION['error'])) {
                echo "<p style='color: #e30613;'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "<p style='color: #00a000;'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }
            if($_SESSION['role'] == "adm") {
                echo "<a href='admin.php'><button>VOIR UTILISATEURS</button></a>";
            }
            $nom = $_SESSION['nom'];
            $prenom = $_SESSION['prenom'];
            $email = $_SESSION['courriel'];
            $telephone = $_SESSION['tel'];
            $date_naissance = $_SESSION['naissance'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
            $id = $_SESSION['id'];
            $modified = false;

            // Check and update each field if present in the POST data
            if (isset($_POST['nom']) && !empty($_POST['nom'])) {
                modifierProfileUtilisateur($id, 'nom', $_POST['nom']);
                $_SESSION['nom'] = $_POST['nom'];
                $modified = true;
            }

            if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
                modifierProfileUtilisateur($id, 'prenom', $_POST['prenom']);
                $_SESSION['prenom'] = $_POST['prenom'];
                $modified = true;
            }

            if (isset($_POST['email']) && !empty($_POST['email'])) {
                modifierProfileUtilisateur($id, 'courriel', $_POST['email']);
                $_SESSION['courriel'] = $_POST['email'];
                $modified = true;
            }

            if (isset($_POST['telephone']) && !empty($_POST['telephone'])) {
                modifierProfileUtilisateur($id, 'tel', $_POST['telephone']);
                $_SESSION['tel'] = $_POST['telephone'];
                $modified = true;
            }

            if (isset($_POST['date_naissance']) && !empty($_POST['date_naissance'])) {
                modifierProfileUtilisateur($id, 'naissance', $_POST['date_naissance']);
                $_SESSION['naissance'] = $_POST['date_naissance'];
                $modified = true;
            }

            if (isset($_POST['mdp']) && !empty($_POST['mdp']) && isset($_POST['cmdp']) && $_POST['mdp'] == $_POST['cmdp']) {
                $mdp_hash = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
                modifierProfileUtilisateur($id, 'mdp', $mdp_hash);
                $modified = true;
            }

            if ($modified) {
                $_SESSION['success'] = "Votre profil a été mis à jour avec succès.";
            }

            header('Location: profil.php');
            exit();
        }
        ?>

        <form id="profile-form" action="profil.php" method="post" class="formulaire-classique">
            <div class="form-field">
                <label for="nom"><strong>Nom :</strong></label>
                <div class="input-groupe">
                    <input type="text" id="nom" name="nom" <?php echo "value='$nom'"; ?>>
                    <button type="button" class="edit-btn" data-field="nom">Modifier</button>
                    <button type="button" class="save-btn" data-field="nom" style="display: none;">Valider</button>
                    <button type="button" class="cancel-btn" data-field="nom" style="display: none;">Annuler</button>
                </div>
            </div>

            <div class="form-field">
                <label for="prenom"><strong>Prénom :</strong></label>
                <div class="input-groupe">
                    <input type="text" id="prenom" name="prenom" <?php echo "value='$prenom'"; ?>>
                    <button type="button" class="edit-btn" data-field="prenom">Modifier</button>
                    <button type="button" class="save-btn" data-field="prenom" style="display: none;">Valider</button>
                    <button type="button" class="cancel-btn" data-field="prenom" style="display: none;">Annuler</button>
                </div>
            </div>

            <div class="form-field">
                <label for="email"><strong>Email :</strong></label>
                <div class="input-groupe">
                    <input type="email" id="email" name="email" <?php echo "value='$email'"; ?>>
                    <button type="button" class="edit-btn" data-field="email">Modifier</button>
                    <button type="button" class="save-btn" data-field="email" style="display: none;">Valider</button>
                    <button type="button" class="cancel-btn" data-field="email" style="display: none;">Annuler</button>
                </div>
            </div>

            <div class="form-field">
                <label for="telephone"><strong>Téléphone :</strong></label>
                <div class="input-groupe">
                    <input type="tel" id="telephone" name="telephone" <?php echo "value='$telephone'"; ?>>
                    <button type="button" class="edit-btn" data-field="telephone">Modifier</button>
                    <button type="button" class="save-btn" data-field="telephone" style="display: none;">Valider</button>
                    <button type="button" class="cancel-btn" data-field="telephone" style="display: none;">Annuler</button>
                </div>
            </div>

            <div class="form-field">
                <label for="date_naissance"><strong>Date de naissance :</strong></label>
                <div class="input-groupe">
                    <input type="date" id="date_naissance" name="date_naissance" <?php echo "value='$date_naissance'"; ?>>
                    <button type="button" class="edit-btn" data-field="date_naissance">Modifier</button>
                    <button type="button" class="save-btn" data-field="date_naissance" style="display: none;">Valider</button>
                    <button type="button" class="cancel-btn" data-field="date_naissance" style="display: none;">Annuler</button>
                </div>
            </div>

            <div class="form-field">
                <label for="mdp"><strong>Mot de passe :</strong></label>
                <div class="input-groupe">
                    <input type="password" id="mdp" name="mdp">
                    <button type="button" class="edit-btn" data-field="mdp">Modifier</button>
                    <button type="button" class="save-btn" data-field="mdp" style="display: none;">Valider</button>
                    <button type="button" class="cancel-btn" data-field="mdp" style="display: none;">Annuler</button>
                </div>
            </div>

            <div class="form-field" id="confirm-password-field" style="display: none;">
                <label for="cmdp"><strong>Confirmer le mot de passe :</strong></label>
                <div class="input-groupe">
                    <input type="password" id="cmdp" name="cmdp">
                </div>
            </div>

            <button type="button" id="submit-all-changes" style="display: none;">Enregistrer les modifications</button>
        </form>
    </div>

    <div>
        <form action="deconnexion.php" method="post" class="deconnexion">
            <a href="connexion.php"><button>Déconnexion</button></a>
        </form>
    </div>
    
    <div class="mes-voyages-container">
        <h2>Mon Panier</h2>
        <?php
            $panier = recupererPanierUtilisateur($_SESSION['id']);
            if (!empty($panier)) {
                echo "<div class='mes-voyages-liste'>";
                foreach ($panier as $voyage_panier) {
                    $voyageid = $voyage_panier["idVoyage"];
                    $voyage = recupererVoyageAvecId($voyageid);
                    echo "<div class='mes-voyages-card'>";
                    echo "<h3 class='mes-voyages-titre'>" . htmlspecialchars($voyage['nom']) . "</h3>";
                    echo "<img src='databases/voyages/" . $voyage['id'] . "/img/profil.jpg' alt='Voyage " . $voyage['id'] . "' width='100%' height='25%'>";
                    echo "<p class='mes-voyages-description'>" . htmlspecialchars($voyage['description']) . "</p>";
                    echo "<p><strong>Période :</strong> " . htmlspecialchars($voyage['debut']) . " - " . htmlspecialchars($voyage['fin']) . "</p>";
                    echo "<p><strong>Tarif :</strong> " . htmlspecialchars($voyage['tarif']) . "€</p>";
                    echo "<a href='paiement.php?panier=1&voyage=" . htmlspecialchars($voyage['id']) . "' class='mes-voyages-btn'>Détails</a>";
                    echo "<br>";
                    echo "<a href='supprimer_panier.php?id=" . htmlspecialchars($voyage['id']) . "' class='mes-voyages-btn2'>Supprimer du panier</a>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p class='mes-voyages-message'>Aucun voyage dans le panier.</p>";
            }
        ?>
        <h2>Mes Voyages Réservés</h2>
        <?php
            $id = $_SESSION['id'];
            // Charger les voyages réservés par l'utilisateur
            $voyages_utilisateur = recupererVoyagesUtilisateur($id);
            
            // Charger les voyages disponibles
            $voyages_disponibles = recupererVoyages();

            // Associer les voyages réservés à leurs informations
            $voyages_reserves = [];
            foreach ($voyages_utilisateur as $reservation) {
                foreach ($voyages_disponibles as $voyage) {
                    if ($voyage['id'] == $reservation['id']) {
                        $voyage['date_reservation'] = $reservation['date'];
                        $voyage['transaction'] = $reservation['transaction'];
                        $voyages_reserves[] = $voyage;
                        break;
                    }
                }
            }

            if (!empty($voyages_reserves)) {
                echo "<div class='mes-voyages-liste'>";
                foreach ($voyages_reserves as $voyage) {
                    echo "<div class='mes-voyages-card'>";
                    echo "<h3 class='mes-voyages-titre'>" . htmlspecialchars($voyage['nom']) . "</h3>";
                    echo "<img src='databases/voyages/" . $voyage['id'] . "/img/profil.jpg' alt='Voyage " . $voyage['id'] . "' width='100%' height='25%'>";
                    echo "<p class='mes-voyages-description'>" . htmlspecialchars($voyage['description']) . "</p>";
                    echo "<p><strong>Réservé le :</strong> " . htmlspecialchars($voyage['date_reservation']) . "</p>";
                    echo "<p><strong>Période :</strong> " . htmlspecialchars($voyage['debut']) . " - " . htmlspecialchars($voyage['fin']) . "</p>";
                    echo "<p><strong>Tarif :</strong> " . htmlspecialchars($voyage['tarif']) . "€</p>";
                    echo "<p><strong>Transaction :</strong> " . htmlspecialchars($voyage['transaction']) . "</p>";
                    echo "<a href='voyage_option.php?id=" . htmlspecialchars($voyage['id']) . "' class='mes-voyages-btn'>Détails</a>";
                    echo "<br>";
                    echo "<a href='annuler_voyage.php?id=" . htmlspecialchars($voyage['id']) . "' class='mes-voyages-btn2'>Annuler réservation</a>";
                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p class='mes-voyages-message'>Aucun voyage réservé.</p>";
            }
        ?>
    </div>
    <?php
        require('requires/footer.php');
    ?>
    <script>
        // Add this function before the DOMContentLoaded event listener
        function setupProfileEditing() {
            // Store original values for potential cancellation
            const originalValues = {};
            // Track which fields have been modified
            const modifiedFields = {};
            // Get all input fields
            const inputFields = document.querySelectorAll('.formulaire-classique input');

            // Initially disable all input fields
            inputFields.forEach(input => {
                input.disabled = true;
                // Store original values
                originalValues[input.id] = input.value;
            });

            // Hide the submit button initially
            const submitButton = document.getElementById('submit-all-changes');
            if (submitButton) submitButton.style.display = 'none';

            // Set up edit buttons
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fieldId = this.dataset.field;
                    const inputField = document.getElementById(fieldId);

                    // Make field editable
                    inputField.disabled = false;
                    inputField.focus();

                    // Show action buttons, hide edit button
                    this.style.display = 'none';
                    document.querySelector(`.save-btn[data-field="${fieldId}"]`).style.display = 'inline-block';
                    document.querySelector(`.cancel-btn[data-field="${fieldId}"]`).style.display = 'inline-block';
                });
            });

            // Set up save buttons
            const saveButtons = document.querySelectorAll('.save-btn');
            saveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fieldId = this.dataset.field;
                    const inputField = document.getElementById(fieldId);

                    // If value changed, mark as modified
                    if (inputField.value !== originalValues[fieldId]) {
                        modifiedFields[fieldId] = inputField.value;

                        // Show the submit button
                        if (submitButton) submitButton.style.display = 'block';
                    }

                    // Disable field
                    inputField.disabled = true;

                    // Hide action buttons, show edit button
                    this.style.display = 'none';
                    document.querySelector(`.cancel-btn[data-field="${fieldId}"]`).style.display = 'none';
                    document.querySelector(`.edit-btn[data-field="${fieldId}"]`).style.display = 'inline-block';
                });
            });

            // Set up cancel buttons
            const cancelButtons = document.querySelectorAll('.cancel-btn');
            cancelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fieldId = this.dataset.field;
                    const inputField = document.getElementById(fieldId);

                    // Restore original value
                    inputField.value = originalValues[fieldId];

                    // Disable field
                    inputField.disabled = true;

                    // Hide action buttons, show edit button
                    this.style.display = 'none';
                    document.querySelector(`.save-btn[data-field="${fieldId}"]`).style.display = 'none';
                    document.querySelector(`.edit-btn[data-field="${fieldId}"]`).style.display = 'inline-block';
                });
            });

            // Set up form submission with modified values
            const profileForm = document.getElementById('profile-form');
            if (profileForm && submitButton) {
                submitButton.addEventListener('click', function() {
                    // Create hidden inputs for each modified field
                    for (const fieldId in modifiedFields) {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = fieldId;
                        hiddenInput.value = modifiedFields[fieldId];
                        profileForm.appendChild(hiddenInput);
                    }

                    // Add an action identifier
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = 'update_profile';
                    profileForm.appendChild(actionInput);

                    // Submit the form
                    profileForm.submit();
                });
            }

            // Special handling for password field
            const mdpEditBtn = document.querySelector('.edit-btn[data-field="mdp"]');
            if (mdpEditBtn) {
                mdpEditBtn.addEventListener('click', function() {
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
                    const pwd = document.getElementById('mdp').value;
                    const confirmPwd = document.getElementById('cmdp').value;

                    if (pwd !== confirmPwd) {
                        alert('Les mots de passe ne correspondent pas.');
                        return;
                    }

                    if (pwd) {
                        modifiedFields['mdp'] = pwd;
                        modifiedFields['cmdp'] = confirmPwd;
                        if (submitButton) submitButton.style.display = 'block';
                    }

                    document.getElementById('confirm-password-field').style.display = 'none';
                });
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            // Theme toggle functionality (copied from resultat_recherche.php)
            const themeToggleBtn = document.querySelector('.yang');

            if (themeToggleBtn) {
                // Ensure we're not breaking any existing handler by using a new listener
                themeToggleBtn.addEventListener('click', function(e) {
                    // Prevent any default behaviors
                    e.preventDefault();
                    e.stopPropagation();

                    // Simple toggle between light and dark
                    const body = document.body;
                    if (body.classList.contains('light-mode')) {
                        body.classList.remove('light-mode');
                        body.classList.add('dark-mode');
                        // Save preference if needed
                        localStorage.setItem('theme', 'dark');
                    } else {
                        body.classList.remove('dark-mode');
                        body.classList.add('light-mode');
                        // Save preference if needed
                        localStorage.setItem('theme', 'light');
                    }

                    // Return false to prevent any other handlers
                    return false;
                }, true); // Use capture phase to ensure this runs first
            }

            // Apply theme from localStorage on page load
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.body.classList.remove('light-mode', 'dark-mode');
                document.body.classList.add(savedTheme + '-mode');
            }

            // Profile editing functionality
            setupProfileEditing();
        });
    </script>
</body>
</html>
