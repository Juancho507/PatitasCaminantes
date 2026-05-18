<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/DisponibilidadDAO.php");

$idPaseador = isset($_GET["idPaseador"]) ? (int)$_GET["idPaseador"] : 0;
$fecha = isset($_GET["fecha"]) ? trim($_GET["fecha"]) : "";
$hora = isset($_GET["hora"]) ? trim($_GET["hora"]) : "";

if ($idPaseador <= 0 || empty($fecha) || empty($hora)) {
    echo json_encode(["disponible" => false, "mensaje" => "Faltan parámetros."]);
    exit;
}

$fechaDT = new DateTime($fecha);
$diaSemana = (int)$fechaDT->format('N');

$horaCompleta = $hora . ":00";

$conexion = new Conexion();
$conexion->abrir();

$conexion->ejecutar("SELECT idDisponibilidad FROM disponibilidad
                      WHERE paseador_idPaseador = $idPaseador
                      AND DiaSemana_idDiaSemana = $diaSemana
                      AND HoraInicio <= '$horaCompleta'
                      AND HoraFin > '$horaCompleta'");

if ($conexion->filas() === 0) {
    $conexion->cerrar();
    echo json_encode(["disponible" => false, "mensaje" => "El paseador no trabaja en este horario."]);
    exit;
}

$fechaStr = $fecha . " " . $horaCompleta;
$conexion->ejecutar("SELECT COUNT(*) FROM Paseo
                      WHERE Paseador_idPaseador = $idPaseador
                      AND EstadoPaseo_idEstadoPaseo IN (1,2)
                      AND FechaInicio < '$fechaStr' + INTERVAL 1 HOUR
                      AND FechaFin > '$fechaStr'");

$fila = $conexion->registro();
$conexion->cerrar();

if ($fila && $fila[0] >= 2) {
    echo json_encode(["disponible" => false, "mensaje" => "El paseador ya tiene 2 paseos en esa hora."]);
    exit;
}

echo json_encode(["disponible" => true, "mensaje" => "Horario disponible."]);
