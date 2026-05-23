<?php
session_start();
require_once(__DIR__ . "/../logica/Paseo.php");
require_once(__DIR__ . "/../logica/EstadoPaseo.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");

header('Content-Type: application/json');

if (!isset($_SESSION["id"]) || $_SESSION["rol"] !== "paseador") {
    echo json_encode(["exito" => false, "mensaje" => "No autorizado."]);
    exit;
}

$idPaseador = (int)$_SESSION["id"];
$idPaseo = isset($_POST["idPaseo"]) ? (int)$_POST["idPaseo"] : 0;
$accion = isset($_POST["accion"]) ? trim($_POST["accion"]) : "";

if ($idPaseo <= 0 || !in_array($accion, ["aceptar", "rechazar", "completar", "cancelarp"])) {
    echo json_encode(["exito" => false, "mensaje" => "Datos inválidos."]);
    exit;
}

$conexion = new Conexion();
$conexion->abrir();

$conexion->ejecutar("SELECT Paseador_idPaseador FROM Paseo WHERE idPaseo = $idPaseo");
$reg = $conexion->registro();
if (!$reg || (int)$reg[0] !== $idPaseador) {
    echo json_encode(["exito" => false, "mensaje" => "Este paseo no te pertenece."]);
    $conexion->cerrar();
    exit;
}

$paseo = new Paseo($idPaseo);

if ($accion === "aceptar") {
    $nuevoEstado = 2;
    if (!$paseo->puedeAceptarPaseo()) {
        echo json_encode(["exito" => false, "mensaje" => "No puedes aceptar este paseo: ya tienes 2 paseos agendados en esta hora o la capacidad de perros está al límite según el nivel de riesgo más alto."]);
        $conexion->cerrar();
        exit;
    }
} elseif ($accion === "completar") {
    $nuevoEstado = 6;
} elseif ($accion === "rechazar") {
    $nuevoEstado = 7;
    $motivo = isset($_POST["motivo"]) ? trim($_POST["motivo"]) : "Sin motivo especificado";
    $conexion->ejecutar("UPDATE Paseo SET MotivoCancelacion = '" . addslashes($motivo) . "' WHERE idPaseo = $idPaseo");
} else {
    $nuevoEstado = 7;
}

$conexion->cerrar();

if ($paseo->actualizarEstado($nuevoEstado)) {
    echo json_encode(["exito" => true, "mensaje" => "Paseo actualizado correctamente."]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al actualizar el paseo."]);
}
