<?php
session_start();
require_once(__DIR__ . '/../persistencia/Conexion.php');

if (
    isset($_SESSION["rol"]) && $_SESSION["rol"] === "administrador" &&
    isset($_POST["id"]) && isset($_POST["accion"])
) {
    $id = (int)$_POST["id"];
    $accion = $_POST["accion"];

    $conexion = new Conexion();
    $conexion->abrir();

    if ($accion === "aprobar") {
        $conexion->ejecutar("UPDATE Paseador SET Estado_idEstado = 2 WHERE idPaseador = $id");
    } elseif ($accion === "rechazar") {
        $conexion->ejecutar("UPDATE Paseador SET Estado_idEstado = 3 WHERE idPaseador = $id");
    } else {
        echo "invalid";
        $conexion->cerrar();
        exit;
    }

    $conexion->cerrar();
    echo "ok";
} else {
    echo "invalid";
}
?>
