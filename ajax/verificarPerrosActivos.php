<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../persistencia/Conexion.php");

$idPerro = isset($_GET["idPerro"]) ? (int)$_GET["idPerro"] : 0;

if ($idPerro <= 0) {
    echo json_encode(["tienePaseosActivos" => false]);
    exit;
}

$conexion = new Conexion();
$conexion->abrir();
$conexion->ejecutar("SELECT COUNT(*) FROM Paseo p
    WHERE (p.perro_idPerro = $idPerro OR p.perro_idPerro2 = $idPerro OR p.perro_idPerro3 = $idPerro OR p.perro_idPerro4 = $idPerro OR p.perro_idPerro5 = $idPerro OR p.perro_idPerro6 = $idPerro)
    AND p.EstadoPaseo_idEstadoPaseo IN (1, 2, 3)");
$fila = $conexion->registro();
$conexion->cerrar();

$tieneActivos = ($fila && $fila[0] > 0);
echo json_encode(["tienePaseosActivos" => $tieneActivos]);
?>
