<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="profil-edition.css">
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_SESSION['id'];
        $action = $_POST['action'];

        if ($action == 'modifier_tous') {
            // Traiter tous les champs potentiellement modifiés
            $champs = [
                'nom' => ['session' => 'nom', 'db' => 'nom'],
                'prenom' => ['session' => 'prenom', 'db' => 'prenom'],
                'email' => ['session' => 'courriel', 'db' => 'courriel'],
                'telephone' => ['session' => 'tel', 'db' => 'tel'],
                'date_naissance' => ['session' => 'naissance', 'db' => 'naissance'],
            ];

            foreach ($champs as $post_key => $info) {
                if (isset($_POST[$post_key]) && $_POST[$post_key] !== $_SESSION[$info['session']]) {
                    $value = $_POST[$post_key];
                    modifierProfileUtilisateur($id, $info['db'], $value);
                    $_SESSION[$info['session']] = $value;
                }
            }

            // Traiter le mot de passe séparément
            if (!empty($_POST['mdp']) && $_POST['mdp'] === $_POST['cmdp']) {
                $value = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
                modifierProfileUtilisateur($id, 'mdp', $value);
            }
        } else {
            // Garder l'ancien code pour compatibilité
            $value = '';
            switch ($action) {
                case 'modifier_nom':
                    // ... code existant ...
                    break;
                // ... autres cases ...
            }
        }

        header('Location: profil.php');
        exit();
    }
    ?>

    <!-- Formulaire principal qui sera soumis -->
    <form id="formulaire_principal_profil" action="profil.php" method="post" class="formulaire-classique">
        <input type="hidden" name="action" id="action_formulaire" value="">

        <!-- Champs cachés pour stocker les modifications -->
        <input type="hidden" id="cache-nom" name="nom" value="<?php echo $nom; ?>">
        <input type="hidden" id="cache-prenom" name="prenom" value="<?php echo $prenom; ?>">
        <input type="hidden" id="cache-email" name="email" value="<?php echo $email; ?>">
        <input type="hidden" id="cache-telephone" name="telephone" value="<?php echo $telephone; ?>">
        <input type="hidden" id="cache-date_naissance" name="date_naissance" value="<?php echo $date_naissance; ?>">
        <input type="hidden" id="cache-mdp" name="mdp" value="">
        <input type="hidden" id="cache-cmdp" name="cmdp" value="">

        <div class="champ_profil">
            <label for="nom"><strong>Nom :</strong></label>
            <div class="groupe_saisie">
                <input type="text" id="nom" value="<?php echo $nom; ?>" class="lecture_seule">
                <button type="button" class="bouton_modifier">Modifier</button>
                <div class="controles_edition">
                    <button type="button" class="bouton_valider">Valider</button>
                    <button type="button" class="bouton_annuler">Annuler</button>
                </div>
            </div>
        </div>

        <div class="champ_profil">
            <label for="prenom"><strong>Prénom :</strong></label>
            <div class="groupe_saisie">
                <input type="text" id="prenom" value="<?php echo $prenom; ?>" class="lecture_seule">
                <button type="button" class="bouton_modifier">Modifier</button>
                <div class="controles_edition">
                    <button type="button" class="bouton_valider">Valider</button>
                    <button type="button" class="bouton_annuler">Annuler</button>
                </div>
            </div>
        </div>

        <div class="champ_profil">
            <label for="email"><strong>Email :</strong></label>
            <div class="groupe_saisie">
                <input type="email" id="email" value="<?php echo $email; ?>" class="lecture_seule">
                <button type="button" class="bouton_modifier">Modifier</button>
                <div class="controles_edition">
                    <button type="button" class="bouton_valider">Valider</button>
                    <button type="button" class="bouton_annuler">Annuler</button>
                </div>
            </div>
        </div>

        <div class="champ_profil">
            <label for="telephone"><strong>Téléphone :</strong></label>
            <div class="groupe_saisie">
                <input type="tel" id="telephone" value="<?php echo $telephone; ?>" class="lecture_seule">
                <button type="button" class="bouton_modifier">Modifier</button>
                <div class="controles_edition">
                    <button type="button" class="bouton_valider">Valider</button>
                    <button type="button" class="bouton_annuler">Annuler</button>
                </div>
            </div>
        </div>

        <div class="champ_profil">
            <label for="date_naissance"><strong>Date de naissance :</strong></label>
            <div class="groupe_saisie">
                <input type="date" id="date_naissance" value="<?php echo $date_naissance; ?>" class="lecture_seule">
                <button type="button" class="bouton_modifier">Modifier</button>
                <div class="controles_edition">
                    <button type="button" class="bouton_valider">Valider</button>
                    <button type="button" class="bouton_annuler">Annuler</button>
                </div>
            </div>
        </div>

        <div class="champ_profil">
            <label for="mdp"><strong>Mot de passe :</strong></label>
            <div class="groupe_saisie">
                <input type="password" id="mdp" value="" class="lecture_seule">
                <button type="button" class="bouton_modifier">Modifier</button>
                <div class="controles_edition">
                    <button type="button" class="bouton_valider">Valider</button>
                    <button type="button" class="bouton_annuler">Annuler</button>
                </div>
            </div>
        </div>

        <div class="champ_profil champ_confirmation_mdp">
            <label for="cmdp"><strong>Confirmer le mot de passe :</strong></label>
            <div class="groupe_saisie">
                <input type="password" id="cmdp" value="" class="lecture_seule">
            </div>
        </div>

        <div class="boutons_formulaire_principal">
            <button type="submit" id="soumettre_modifications" onclick="return soumettreFormulaire()">Enregistrer les modifications</button>
        </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Stockage des valeurs originales et suivi des modifications
            const valeursOriginales = {};
            const champsFormulaire = document.querySelectorAll('.champ_profil');
            let modificationsEffectuees = false;

            // Initialisation des champs en lecture seule
            champsFormulaire.forEach(champ => {
                const saisie = champ.querySelector('input');
                const champId = saisie.id;
                valeursOriginales[champId] = saisie.value;
                saisie.setAttribute('readonly', true);
                saisie.classList.add('lecture_seule');
            });

            // Cacher initialement le bouton de soumission
            const boutonSoumettre = document.getElementById('soumettre_modifications');
            if(boutonSoumettre) boutonSoumettre.style.display = 'none';

            // Gestionnaire de clic sur le bouton modifier
            document.querySelectorAll('.bouton_modifier').forEach(btn => {
                btn.addEventListener('click', function() {
                    const conteneurChamp = this.closest('.champ_profil');
                    const saisie = conteneurChamp.querySelector('input');
                    const controlesEdition = conteneurChamp.querySelector('.controles_edition');

                    // Afficher le champ de confirmation si on modifie le mot de passe
                    if(saisie.id === 'mdp' && saisie.value === '') {
                        document.querySelector('.champ_confirmation_mdp').style.display = 'block';
                    }

                    // Activer l'édition
                    saisie.removeAttribute('readonly');
                    saisie.classList.remove('lecture_seule');
                    saisie.focus();

                    // Afficher les boutons valider/annuler, cacher le bouton modifier
                    this.style.display = 'none';
                    controlesEdition.style.display = 'inline-block';
                });
            });

            // Gestionnaire de clic sur le bouton valider
            document.querySelectorAll('.bouton_valider').forEach(btn => {
                btn.addEventListener('click', function() {
                    const conteneurChamp = this.closest('.champ_profil');
                    const saisie = conteneurChamp.querySelector('input');
                    const boutonModifier = conteneurChamp.querySelector('.bouton_modifier');
                    const controlesEdition = conteneurChamp.querySelector('.controles_edition');

                    // Remettre le champ en lecture seule
                    saisie.setAttribute('readonly', true);
                    saisie.classList.add('lecture_seule');

                    // Vérifier si la valeur a changé
                    if(saisie.value !== valeursOriginales[saisie.id]) {
                        modificationsEffectuees = true;
                        conteneurChamp.classList.add('modifie');
                        // Mettre à jour le champ caché dans le formulaire principal
                        const champCache = document.getElementById(`cache-${saisie.id}`);
                        if(champCache) champCache.value = saisie.value;

                        // Afficher le bouton soumettre
                        if(boutonSoumettre) boutonSoumettre.style.display = 'block';
                    }

                    // Afficher le bouton modifier, cacher valider/annuler
                    boutonModifier.style.display = 'inline-block';
                    controlesEdition.style.display = 'none';
                });
            });

            // Gestionnaire de clic sur le bouton annuler
            document.querySelectorAll('.bouton_annuler').forEach(btn => {
                btn.addEventListener('click', function() {
                    const conteneurChamp = this.closest('.champ_profil');
                    const saisie = conteneurChamp.querySelector('input');
                    const boutonModifier = conteneurChamp.querySelector('.bouton_modifier');
                    const controlesEdition = conteneurChamp.querySelector('.controles_edition');

                    // Cacher le champ de confirmation si on annule la modification du mot de passe
                    if(saisie.id === 'mdp') {
                        document.querySelector('.champ_confirmation_mdp').style.display = 'none';
                    }

                    // Restaurer la valeur originale
                    saisie.value = valeursOriginales[saisie.id];

                    // Remettre le champ en lecture seule
                    saisie.setAttribute('readonly', true);
                    saisie.classList.add('lecture_seule');

                    // Afficher le bouton modifier, cacher valider/annuler
                    boutonModifier.style.display = 'inline-block';
                    controlesEdition.style.display = 'none';
                });
            });
        });

        function soumettreFormulaire() {
            // Gestion de la validation du mot de passe
            if(document.getElementById('cache-mdp').value) {
                if(document.getElementById('cache-mdp').value !== document.getElementById('cache-cmdp').value) {
                    alert('Les mots de passe ne correspondent pas!');
                    return false;
                }
                document.getElementById('action_formulaire').value = 'modifier_tous';
            } else {
                // Marquer l'action comme modification multiple
                document.getElementById('action_formulaire').value = 'modifier_tous';
            }

            return true;
        }
    </script>
</body>
</html>
