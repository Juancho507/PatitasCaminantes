<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../persistencia/Conexion.php");

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
$tipo = isset($_POST["tipo"]) ? trim($_POST["tipo"]) : "";
$activo = isset($_POST["activo"]) ? (int)$_POST["activo"] : 0;

if ($id <= 0 || empty($tipo)) {
    echo json_encode(["exito" => false, "mensaje" => "Datos invalidos."]);
    exit;
}

$conexion = new Conexion();
$conexion->abrir();

if ($tipo === "dueño") {
    $conexion->ejecutar("UPDATE Dueño SET Activo = $activo WHERE idDueño = $id");
} elseif ($tipo === "paseador") {
    $conexion->ejecutar("UPDATE Paseador SET Activo = $activo WHERE idPaseador = $id");
} else {
    echo json_encode(["exito" => false, "mensaje" => "Tipo de usuario invalido."]);
    $conexion->cerrar();
    exit;
}

$conexion->cerrar();
echo json_encode(["exito" => true, "mensaje" => "Estado actualizado."]);
