<?php
session_start();
require_once 'requires/json_utilities.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Vous devez selectionner un voyage à annuler";
    header('Location: profil.php');
    exit();
}

if (!isset($_SESSION['id'])) {
    $_SESSION['error'] = "Vous devez vous connecter à votre compte";
    header('Location: connexion.php');
    exit();
}

$idVoyage = $_GET['id'];
$idUtilisateur = $_SESSION['id'];

annulerVoyageUtilisateur($idUtilisateur, $idVoyage);

$_SESSION['success'] = "Voyage annulé avec succès";
header('Location: profil.php');
exit();
    

?>