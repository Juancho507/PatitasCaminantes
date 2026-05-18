<?php
require_once(__DIR__ . "/../logica/Paseo.php");
require_once(__DIR__ . "/../logica/EstadoPaseo.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");

header('Content-Type: application/json');

$idPaseo = isset($_POST["idPaseo"]) ? (int)$_POST["idPaseo"] : 0;
$accion = isset($_POST["accion"]) ? trim($_POST["accion"]) : "";

if ($idPaseo <= 0 || !in_array($accion, ["aceptar", "rechazar", "completar", "cancelarp"])) {
    echo json_encode(["exito" => false, "mensaje" => "Datos inválidos."]);
    exit;
}

$paseo = new Paseo($idPaseo);

if ($accion === "aceptar") {
    $nuevoEstado = 2;
    if (!$paseo->puedeAceptarPaseo()) {
        echo json_encode(["exito" => false, "mensaje" => "Ya tienes 2 paseos agendados en esta hora."]);
        exit;
    }
} elseif ($accion === "completar") {
    $nuevoEstado = 4;
} elseif ($accion === "cancelarp") {
    $nuevoEstado = 6;
} else {
    $nuevoEstado = 6;
    $motivo = isset($_POST["motivo"]) ? trim($_POST["motivo"]) : "Sin motivo especificado";
}

if ($paseo->actualizarEstado($nuevoEstado)) {
    echo json_encode(["exito" => true, "mensaje" => "Paseo actualizado correctamente."]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al actualizar el paseo."]);
}
