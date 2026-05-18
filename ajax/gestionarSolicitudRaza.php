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
        $tamañoId = isset($_POST["tamaño"]) ? (int)$_POST["tamaño"] : 0;
        if ($tamañoId <= 0) {
            echo "notamaño";
            $conexion->cerrar();
            exit;
        }
        $conexion->ejecutar("SELECT NombreRaza FROM solicitudraza WHERE idSolicitud = $id");
        if ($conexion->filas() > 0) {
            $row = $conexion->registro();
            $nombreRaza = $row[0];
            $nombreSeguro = addslashes($nombreRaza);

            $conexion->ejecutar("SELECT COUNT(*) FROM Raza WHERE Raza LIKE '{$nombreSeguro}%'");
            $existe = $conexion->registro();
            if ($existe && $existe[0] > 0) {
                echo "duplicate";
                $conexion->cerrar();
                exit;
            }

            $conexion->ejecutar("INSERT INTO Raza (Raza, Tamaño_idTamaño) VALUES ('{$nombreSeguro} Hembra', $tamañoId)");
            $conexion->ejecutar("INSERT INTO Raza (Raza, Tamaño_idTamaño) VALUES ('{$nombreSeguro} Masculino', $tamañoId)");
            $conexion->ejecutar("UPDATE solicitudraza SET EstadoSolicitud_idEstadoSolicitud = 2 WHERE idSolicitud = $id");
            echo "ok";
        } else {
            echo "notfound";
        }
    } elseif ($accion === "rechazar") {
        $conexion->ejecutar("UPDATE solicitudraza SET EstadoSolicitud_idEstadoSolicitud = 3 WHERE idSolicitud = $id");
        echo "ok";
    } else {
        echo "invalid";
    }

    $conexion->cerrar();
} else {
    echo "invalid";
}
?>
