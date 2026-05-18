<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../persistencia/Conexion.php");

$idPaseo = isset($_POST["idPaseo"]) ? (int)$_POST["idPaseo"] : 0;
$motivo = isset($_POST["motivo"]) ? trim($_POST["motivo"]) : "";
$cancelador = isset($_POST["cancelador"]) ? trim($_POST["cancelador"]) : "";

if ($idPaseo <= 0 || empty($motivo) || empty($cancelador)) {
    echo json_encode(["exito" => false, "mensaje" => "Faltan datos requeridos."]);
    exit;
}

$conexion = new Conexion();
$conexion->abrir();

$conexion->ejecutar("SELECT p.FechaInicio, p.EstadoPaseo_idEstadoPaseo, p.Paseador_idPaseador, per.Dueño_idDueño
    FROM Paseo p
    INNER JOIN Perro per ON p.perro_idPerro = per.idPerro
    WHERE p.idPaseo = $idPaseo LIMIT 1");
$paseoData = $conexion->registro();

if (!$paseoData) {
    echo json_encode(["exito" => false, "mensaje" => "Paseo no encontrado."]);
    $conexion->cerrar();
    exit;
}

$fechaInicio = $paseoData[0];
$estadoActual = $paseoData[1];
$idPaseador = $paseoData[2];
$idDueño = $paseoData[3];

if ($cancelador === "dueño") {
    $nuevoEstado = 5;
} else {
    $nuevoEstado = 6;
}

$conexion->ejecutar("UPDATE Paseo SET EstadoPaseo_idEstadoPaseo = $nuevoEstado, MotivoCancelacion = '" . addslashes($motivo) . "' WHERE idPaseo = $idPaseo");

$ahora = new DateTime();
$fechaInicioDT = new DateTime($fechaInicio);
$diffHoras = ($fechaInicioDT->getTimestamp() - $ahora->getTimestamp()) / 3600;

$mensaje = "Paseo cancelado exitosamente.";
if ($cancelador === "dueño" && $diffHoras < 2 && $diffHoras >= 0) {
    $conexion->ejecutar("UPDATE Dueño SET Activo = 0 WHERE idDueño = $idDueño");
    $mensaje = "Paseo cancelado. Su cuenta ha sido bloqueada por cancelar con menos de 2 horas de anticipacion. Contacte al administrador.";
}

$conexion->cerrar();
echo json_encode(["exito" => true, "mensaje" => $mensaje]);
