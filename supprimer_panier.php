<?php
session_start();
require('requires/json_utilities.php');
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $id = $_SESSION['id'];
} else {
    $_SESSION['error'] =  "Vous devez être connecté pour gérer le panier";
    header('Location: connexion.php');
    exit;
}

if (isset($_GET['id'])) {
    $voyageId = $_GET['id'];
} else {
    $_SESSION['error'] = "Voyage non trouvé";
    header('Location: profil.php');
    exit;
}

if (existePanierVoyageUtilisateur($id, $voyageId)) {
    supprimerVoyagePanier($id, $voyageId); // Supprimer le voyage du panier de l'utilisateur
    $_SESSION['success'] = "Le voyage a été supprimé du panier avec succès.";
} else {
    $_SESSION['error'] = "Le voyage n'est pas dans le panier.";
}
header('Location: profil.php'); // Rediriger vers la page du profil
exit;
?>