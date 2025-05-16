<?php
require('requires/json_utilities.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();
    $voyageId = $_POST['voyageId'];
    $options = $_POST['options'] ?? [];
    $_SESSION['options'] = $_POST['options'];
    print(json_encode($options));
    
    header("Location: paiement.php?voyage=$voyageId&maj=1");
    exit;
} else {
    die("Accès non autorisé.");
}
?>
