<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "dueño" || !isset($_POST["idPaseo"])) {
    echo json_encode(["exito" => false, "mensaje" => "Acceso denegado."]);
    exit;
}

require_once(__DIR__ . "/../persistencia/Conexion.php");

$idPaseo = (int)$_POST["idPaseo"];
$idDueño = (int)$_SESSION["id"];

$conexion = new Conexion();
$conexion->abrir();

$conexion->ejecutar("SELECT Estado_idEstado, FechaInicio FROM Paseo WHERE idPaseo = $idPaseo");
$paseo = $conexion->registro();

if (!$paseo) {
    echo json_encode(["exito" => false, "mensaje" => "Paseo no encontrado."]);
    $conexion->cerrar();
    exit;
}

$estadoActual = (int)$paseo[0];
if ($estadoActual !== 1 && $estadoActual !== 2) {
    echo json_encode(["exito" => false, "mensaje" => "Este paseo ya no puede cancelarse."]);
    $conexion->cerrar();
    exit;
}

$fechaInicio = new DateTime($paseo[1]);
$ahora = new DateTime();
$diferencia = $ahora->diff($fechaInicio);
$horasRestantes = ($diferencia->days * 24) + $diferencia->h + ($diferencia->i / 60);

if ($horasRestantes < 2) {
    $conexion->ejecutar("UPDATE Dueño SET Estado_idEstado = 4 WHERE idDueño = $idDueño");
    $conexion->ejecutar("UPDATE Paseo SET Estado_idEstado = 3 WHERE idPaseo = $idPaseo");
    echo json_encode(["exito" => true, "mensaje" => "Cancelaste con menos de 2 horas de anticipación. Tu cuenta ha sido bloqueada. Contacta al administrador."]);
    $conexion->cerrar();
    exit;
}

$conexion->ejecutar("UPDATE Paseo SET Estado_idEstado = 3 WHERE idPaseo = $idPaseo");

if ($conexion->afectadas() > 0) {
    echo json_encode(["exito" => true, "mensaje" => "Paseo cancelado correctamente."]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al cancelar el paseo."]);
}

$conexion->cerrar();
?>
