<?php
session_start();
require_once(__DIR__ . '/../persistencia/Conexion.php');

if (
    isset($_SESSION["rol"]) && $_SESSION["rol"] === "administrador" &&
    isset($_POST["id"]) && isset($_POST["activo"])
) {
    $id = (int)$_POST["id"];
    $activo = (int)$_POST["activo"];

    $conexion = new Conexion();
    $conexion->abrir();
    $conexion->ejecutar("UPDATE Dueño SET Activo = $activo WHERE idDueño = $id");
    $conexion->cerrar();
    echo "ok";
} else {
    echo "invalid";
}
