<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../logica/Localidad.php");

$idCiudad = isset($_GET["idCiudad"]) ? (int)$_GET["idCiudad"] : 0;

if ($idCiudad <= 0) {
    echo json_encode([]);
    exit;
}

$localidad = new Localidad();
$lista = $localidad->consultarPorCiudad($idCiudad);

$resultado = [];
foreach ($lista as $loc) {
    $resultado[] = [
        "id" => $loc->getId(),
        "nombre" => $loc->getNombre()
    ];
}

echo json_encode($resultado);
