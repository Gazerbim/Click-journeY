<?php
session_start();
require("requires/json_utilities.php");

function modifierProfileUtilisateur($id, $field, $value) {
    $path = "databases/users.json";
    $content = lireFichierJson($path);
    foreach ($content as &$user) {
        if ($user['id'] == $id) {
            $user[$field] = $value;
            break;
        }
    }
    file_put_contents($path, json_encode($content));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['id'];
    $action = $_POST['action'];
    $value = '';

    switch ($action) {
        case 'modifier_nom':
            $value = $_POST['nom'];
            modifierProfileUtilisateur($id, 'nom', $value);
            $_SESSION['nom'] = $value;
            break;
        case 'modifier_prenom':
            $value = $_POST['prenom'];
            modifierProfileUtilisateur($id, 'prenom', $value);
            $_SESSION['prenom'] = $value;
            break;
        case 'modifier_email':
            $value = $_POST['email'];
            modifierProfileUtilisateur($id, 'courriel', $value);
            $_SESSION['courriel'] = $value;
            break;
        case 'modifier_telephone':
            $value = $_POST['telephone'];
            modifierProfileUtilisateur($id, 'tel', $value);
            $_SESSION['tel'] = $value;
            break;
        case 'modifier_date_naissance':
            $value = $_POST['date_naissance'];
            modifierProfileUtilisateur($id, 'naissance', $value);
            $_SESSION['naissance'] = $value;
            break;
        case 'modifier_mdp':
            if ($_POST['mdp'] == $_POST['cmdp']) {
                $value = $_POST['mdp'];
                modifierProfileUtilisateur($id, 'mdp', $value);
            }
            break;
    }
    header('Location: profil.php');
    exit();
}
?>