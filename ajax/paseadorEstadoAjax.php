<?php
session_start();
require_once(__DIR__ . '/../persistencia/Conexion.php');
require_once(__DIR__ . '/../persistencia/PaseadorDAO.php');

header('Content-Type: application/json');

if (
    isset($_SESSION["rol"]) && $_SESSION["rol"] === "administrador" &&
    isset($_POST["idPaseador"]) && isset($_POST["estado"])
) {
    $id = (int)$_POST["idPaseador"];
    $estado = (int)$_POST["estado"];

    $conexion = new Conexion();
    $conexion->abrir();

    if ($estado == 4) {
        $conexion->ejecutar("SELECT COUNT(*) FROM Paseo WHERE Paseador_idPaseador = $id AND Estado_idEstado IN (1,2) AND FechaInicio > NOW()");
        $fila = $conexion->registro();
        if ($fila && $fila[0] > 0) {
            $conexion->cerrar();
            echo json_encode(["exito" => false, "mensaje" => "No se puede deshabilitar el paseador porque tiene $fila[0] paseo(s) pendiente(s)."]);
            exit;
        }
    }

    $paseadorDAO = new PaseadorDAO($id, "", "", "", "", "", "", "", $estado);
    try {
        $conexion->ejecutar($paseadorDAO->actualizarEstado());
        $conexion->cerrar();
        echo json_encode(["exito" => true, "mensaje" => "ok"]);
    } catch (Exception $e) {
        echo json_encode(["exito" => false, "mensaje" => "error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["exito" => false, "mensaje" => "invalid"]);
}
