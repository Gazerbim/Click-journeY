<?php
    #$voyage = array(array("id"=>123456, "nom"=>"Les pires Krous de France", "description"=>"Le voyage qui vous fera decouvrir les pires Krous de France", "etapes"=>array(), "tarif"=>125, "debut"=>"12/05/2025", "fin"=>"30/05/2025"));
    require_once("requires/json_utilities.php");
    $voyage = recupererEtapesVoyage(123456);
    echo json_encode($voyage);
    echo "<br>";
    $uvoyages = recupererVoyagesUtilisateur(12651);
    echo json_encode($uvoyages);



?>