<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once("../logica/Paseo.php");

$rol = "dueño";
$id = $_SESSION["id"];
$filtro = isset($_GET["q"]) ? trim($_GET["q"]) : "";
$palabras = array_filter(array_map("trim", explode(" ", $filtro)));

$p = new Paseo();
$datos = $p->buscarHistorial($rol, $id, $palabras);

header('Content-Type: application/json');
echo json_encode(array_map(function($paseo) {
    $fechaInicio = new DateTime($paseo->getFechaInicio());
    $fechaFin = new DateTime($paseo->getFechaFin());
    return [
        "id" => $paseo->getId(),
        "fecha" => $fechaInicio->format('Y-m-d'),
        "inicio" => $fechaInicio->format('H:i'),
        "fin" => $fechaFin->format('H:i'),
        "perro" => $paseo->getNombrePerro(),
        "paseador" => $paseo->getPaseador(),
        "precio" => number_format($paseo->getPrecio(), 0),
        "estado" => $paseo->getEstadoPaseo()
    ];
}, $datos));
