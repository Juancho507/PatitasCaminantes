<?php
require_once(__DIR__ . "/../logica/Disponibilidad.php");

header('Content-Type: application/json');

$idPaseador = isset($_POST["idPaseador"]) ? (int)$_POST["idPaseador"] : 0;
$idDiaSemana = isset($_POST["idDiaSemana"]) ? (int)$_POST["idDiaSemana"] : 0;
$horaInicio = isset($_POST["horaInicio"]) ? trim($_POST["horaInicio"]) : "";
$horaFin = isset($_POST["horaFin"]) ? trim($_POST["horaFin"]) : "";

if ($idPaseador <= 0 || $idDiaSemana <= 0 || empty($horaInicio) || empty($horaFin)) {
    echo json_encode(["exito" => false, "mensaje" => "Datos incompletos."]);
    exit;
}

if ($horaInicio >= $horaFin) {
    echo json_encode(["exito" => false, "mensaje" => "La hora de inicio debe ser menor que la hora de fin."]);
    exit;
}

$d = new Disponibilidad(0, $horaInicio, $horaFin, $idPaseador, $idDiaSemana);
$d->insertar();

echo json_encode(["exito" => true, "mensaje" => "Disponibilidad guardada correctamente."]);
