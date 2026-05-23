<?php
session_start();
require_once(__DIR__ . '/../persistencia/Conexion.php');

if (!isset($_SESSION["rol"]) || !isset($_POST["id"]) || !isset($_POST["activo"])) {
    echo "invalid";
    exit;
}

$id = (int)$_POST["id"];
$activo = (int)$_POST["activo"];

$conexion = new Conexion();
$conexion->abrir();

if ($_SESSION["rol"] === "dueño") {
    $idDueño = (int)$_SESSION["id"];
    $conexion->ejecutar("UPDATE Perro SET Estado_idEstado = $activo WHERE idPerro = $id AND Dueño_idDueño = $idDueño");
} elseif ($_SESSION["rol"] === "administrador") {
    $conexion->ejecutar("UPDATE Perro SET Estado_idEstado = $activo WHERE idPerro = $id");
} else {
    echo "invalid";
    $conexion->cerrar();
    exit;
}

echo ($conexion->afectadas() > 0) ? "ok" : "error";
$conexion->cerrar();
?>
