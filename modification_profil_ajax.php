<?php
session_start();
require("requires/json_utilities.php");

// Check if user is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté']);
    exit;
}

// Check if it's an AJAX request with POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profil') {
    $id = $_SESSION['id'];
    $modified = false;
    $updates = [];

    // Process each field
    if (isset($_POST['nom']) && !empty($_POST['nom'])) {
        modifierProfileUtilisateur($id, 'nom', $_POST['nom']);
        $_SESSION['nom'] = $_POST['nom'];
        $updates['nom'] = $_POST['nom'];
        $modified = true;
    }

    if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
        modifierProfileUtilisateur($id, 'prenom', $_POST['prenom']);
        $_SESSION['prenom'] = $_POST['prenom'];
        $updates['prenom'] = $_POST['prenom'];
        $modified = true;
    }

    if (isset($_POST['email']) && !empty($_POST['email'])) {
        modifierProfileUtilisateur($id, 'courriel', $_POST['email']);
        $_SESSION['courriel'] = $_POST['email'];
        $updates['email'] = $_POST['email'];
        $modified = true;
    }

    if (isset($_POST['telephone']) && !empty($_POST['telephone'])) {
        modifierProfileUtilisateur($id, 'tel', $_POST['telephone']);
        $_SESSION['tel'] = $_POST['telephone'];
        $updates['telephone'] = $_POST['telephone'];
        $modified = true;
    }

    if (isset($_POST['date_naissance']) && !empty($_POST['date_naissance'])) {
        modifierProfileUtilisateur($id, 'naissance', $_POST['date_naissance']);
        $_SESSION['naissance'] = $_POST['date_naissance'];
        $updates['date_naissance'] = $_POST['date_naissance'];
        $modified = true;
    }

    if (isset($_POST['mdp']) && !empty($_POST['mdp']) && isset($_POST['cmdp']) && $_POST['mdp'] === $_POST['cmdp']) {
        $mdp_hash = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
        modifierProfileUtilisateur($id, 'mdp', $mdp_hash);
        $modified = true;
    }

    if ($modified) {
        echo json_encode(['success' => true, 'message' => 'Votre profil a été mis à jour avec succès.', 'updates' => $updates]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Aucune modification n\'a été effectuée.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requête invalide.']);
}
?>