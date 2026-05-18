<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../persistencia/Conexion.php");

$documento = isset($_GET["documento"]) ? trim($_GET["documento"]) : "";

if (empty($documento)) {
    echo json_encode(["existe" => false]);
    exit;
}

$conexion = new Conexion();
$conexion->abrir();
$documento = $conexion->conexion->real_escape_string($documento);
$conexion->ejecutar("SELECT idPaseador FROM paseador WHERE NroDocumento = '$documento' UNION SELECT idDueño FROM dueño WHERE NroDocumento = '$documento'");
$existe = $conexion->filas() > 0;
$conexion->cerrar();

echo json_encode(["existe" => $existe]);
