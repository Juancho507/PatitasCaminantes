<?php
session_start();
require_once(__DIR__ . '/../persistencia/Conexion.php');
require_once(__DIR__ . '/../persistencia/PaseadorDAO.php');

if (
    isset($_SESSION["rol"]) && $_SESSION["rol"] === "administrador" &&
    isset($_POST["idPaseador"]) && isset($_POST["estado"])
) {
    $id = $_POST["idPaseador"];
    $estado = $_POST["estado"];

    $conexion = new Conexion();
    $conexion->abrir();

    $paseadorDAO = new PaseadorDAO($id, "", "", "", "", "", "", "", $estado);
    try {
        $conexion->ejecutar($paseadorDAO->actualizarEstado());
        $conexion->cerrar();
        echo "ok";
    } catch (Exception $e) {
        echo "error: " . $e->getMessage();
    }
} else {
    echo "invalid";
}
