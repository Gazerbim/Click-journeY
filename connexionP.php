
<?php
session_start();
require_once 'requires/json_utilities.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mdp = isset($_POST['mdp']) ? trim($_POST['mdp']) : '';

   
    if (empty($email) || empty($mdp)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs";
        header('Location: connexion.php');
        exit;
    }

    
    $utilisateurs = lireFichierJson("./databases/users.json");

    if (!$utilisateurs) {
        $_SESSION['error'] = "Erreur de lecture des utilisateurs";
        header('Location: connexion.php');
        exit;
    }

    $utilisateur_trouve = checkIdentifiants($email, $mdp);
    
    if ($utilisateur_trouve) {
        header('Location: index.html'); 
        exit;
    } else {
        $_SESSION['error'] = "Email ou mot de passe incorrect";
        header('Location: connexion.php');         exit;
    }
}
?>

