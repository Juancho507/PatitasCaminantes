<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["id"]) || $_SESSION["rol"] !== "administrador") {
    echo json_encode(["exito" => false, "mensaje" => "No autorizado."]);
    exit;
}

require_once(__DIR__ . "/../persistencia/Conexion.php");
require_once(__DIR__ . "/../logica/Paseador.php");

$conexion = new Conexion();
$conexion->abrir();

$accion = isset($_POST["accion"]) ? trim($_POST["accion"]) : "";

if ($accion === "listar") {
    $conexion->ejecutar("SELECT p.idPaseador, p.Nombre, p.Apellido, p.Correo, p.Contacto, p.Certificados, p.AprobadoPeligroso
                          FROM Paseador p
                          WHERE p.Estado_idEstado IN (2, 4)
                          ORDER BY p.Nombre ASC");
    $lista = [];
    while ($reg = $conexion->registro()) {
        $lista[] = [
            "id" => $reg[0],
            "nombre" => $reg[1],
            "apellido" => $reg[2],
            "correo" => $reg[3],
            "contacto" => $reg[4],
            "certificados" => $reg[5] ?? "",
            "aprobado" => (int)$reg[6]
        ];
    }
    echo json_encode(["exito" => true, "lista" => $lista]);
    $conexion->cerrar();
    exit;
}

if ($accion === "toggle") {
    $idPaseador = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
    $nuevoEstado = isset($_POST["estado"]) ? (int)$_POST["estado"] : 0;
    if ($idPaseador <= 0) {
        echo json_encode(["exito" => false, "mensaje" => "ID inválido."]);
        $conexion->cerrar();
        exit;
    }
    $conexion->ejecutar("UPDATE Paseador SET AprobadoPeligroso = $nuevoEstado WHERE idPaseador = $idPaseador");
    echo json_encode(["exito" => true, "mensaje" => "Estado actualizado."]);
    $conexion->cerrar();
    exit;
}

echo json_encode(["exito" => false, "mensaje" => "Acción no válida."]);
$conexion->cerrar();
