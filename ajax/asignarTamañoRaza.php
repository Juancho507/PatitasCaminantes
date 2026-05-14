<?php
require_once(__DIR__ . "/../logica/Raza.php");

if (isset($_POST["idRaza"]) && isset($_POST["idTamaño"])) {
    $idRaza = $_POST["idRaza"];
    $idTamaño = $_POST["idTamaño"];
    
    $raza = new Raza($idRaza);
    $raza->asignarTamaño($idTamaño);
    
    echo "ok";
} else {
    echo "error";
}
?>