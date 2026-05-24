<?php
session_start();
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != "administrador") {
    echo json_encode(["exito" => false, "mensaje" => "No autorizado"]);
    exit();
}

require_once "../logica/Ciudad.php";
require_once "../logica/Localidad.php";
require_once "../persistencia/Conexion.php";

$conexion = new Conexion();
$conexion->abrir();
$conexion->ejecutar("SET NAMES utf8");

$accion = $_POST["accion"] ?? "";

switch ($accion) {
    case "ver_vista":
        $vista = $_POST["vista"] ?? "";
        $vistasPermitidas = [
            "vw_estadisticas_admin",
            "vw_aspirantes_pendientes",
            "vw_paseadores_activos",
            "vw_perros_con_dueno",
            "vw_paseos_detalle",
            "vw_paseos_pendientes_vencer"
        ];
        if (!in_array($vista, $vistasPermitidas)) {
            echo json_encode(["exito" => false, "mensaje" => "Vista no permitida"]);
            exit();
        }
        try {
            $conexion->ejecutar("SELECT * FROM `$vista`");
            $filas = $conexion->getResultado();
            if ($filas && $filas->num_rows > 0) {
                $columnas = [];
                while ($info = $filas->fetch_field()) {
                    $columnas[] = $info->name;
                }
                $datos = [];
                $filas->data_seek(0);
                while ($row = $filas->fetch_assoc()) {
                    $datos[] = $row;
                }
                echo json_encode(["exito" => true, "columnas" => $columnas, "datos" => $datos]);
            } else {
                echo json_encode(["exito" => true, "columnas" => [], "datos" => [], "mensaje" => "La vista no contiene datos."]);
            }
        } catch (Exception $e) {
            echo json_encode(["exito" => false, "mensaje" => "Error: " . $e->getMessage()]);
        }
        break;

    case "ver_triggers":
        try {
            $conexion->ejecutar("SHOW TRIGGERS");
            $filas = $conexion->getResultado();
            $datos = [];
            if ($filas) {
                while ($row = $filas->fetch_assoc()) {
                    $datos[] = $row;
                }
            }
            echo json_encode(["exito" => true, "datos" => $datos]);
        } catch (Exception $e) {
            echo json_encode(["exito" => false, "mensaje" => "Error: " . $e->getMessage()]);
        }
        break;

    case "ejecutar_sql":
        $sql = trim($_POST["sql"] ?? "");
        if (empty($sql)) {
            echo json_encode(["exito" => false, "mensaje" => "SQL vacío"]);
            exit();
        }
        $sqlUpper = strtoupper($sql);
        if (strpos($sqlUpper, "SELECT") !== 0 && strpos($sqlUpper, "SHOW") !== 0 && strpos($sqlUpper, "DESCRIBE") !== 0) {
            echo json_encode(["exito" => false, "mensaje" => "Solo se permiten consultas SELECT, SHOW o DESCRIBE"]);
            exit();
        }
        try {
            $conexion->ejecutar($sql);
            $resultado = $conexion->getResultado();
            if ($resultado && method_exists($resultado, 'fetch_assoc')) {
                $columnas = [];
                while ($info = $resultado->fetch_field()) {
                    $columnas[] = $info->name;
                }
                $datos = [];
                $resultado->data_seek(0);
                while ($row = $resultado->fetch_assoc()) {
                    $datos[] = $row;
                }
                echo json_encode(["exito" => true, "columnas" => $columnas, "datos" => $datos, "filas" => count($datos)]);
            } else {
                echo json_encode(["exito" => true, "mensaje" => "Consulta ejecutada. Filas afectadas: " . $conexion->afectadas()]);
            }
        } catch (Exception $e) {
            echo json_encode(["exito" => false, "mensaje" => "Error SQL: " . $e->getMessage()]);
        }
        break;

    case "ver_log":
        try {
            $conexion->ejecutar("SELECT * FROM `log_paseo` ORDER BY idLog DESC LIMIT 100");
            $filas = $conexion->getResultado();
            if ($filas && $filas->num_rows > 0) {
                $columnas = [];
                while ($info = $filas->fetch_field()) {
                    $columnas[] = $info->name;
                }
                $datos = [];
                $filas->data_seek(0);
                while ($row = $filas->fetch_assoc()) {
                    $datos[] = $row;
                }
                echo json_encode(["exito" => true, "columnas" => $columnas, "datos" => $datos]);
            } else {
                echo json_encode(["exito" => true, "columnas" => [], "datos" => [], "mensaje" => "No hay registros en el log."]);
            }
        } catch (Exception $e) {
            echo json_encode(["exito" => false, "mensaje" => "Error: " . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(["exito" => false, "mensaje" => "Acción no válida"]);
}
$conexion->cerrar();
