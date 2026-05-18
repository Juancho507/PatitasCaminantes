<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../persistencia/Conexion.php");

$idPerros = isset($_GET["idPerros"]) ? trim($_GET["idPerros"]) : "";

if (empty($idPerros)) {
    echo json_encode(["capacidadMax" => 6, "nivelMaximo" => "Bajo", "mensaje" => "Sin perros seleccionados."]);
    exit;
}

$ids = array_map('intval', explode(",", $idPerros));
$ids = array_filter($ids, function($v) { return $v > 0; });

if (empty($ids)) {
    echo json_encode(["capacidadMax" => 6, "nivelMaximo" => "Bajo", "mensaje" => "Sin perros seleccionados."]);
    exit;
}

$idsStr = implode(",", $ids);

$conexion = new Conexion();
$conexion->abrir();

$conexion->ejecutar("SELECT pg.Nivel FROM Perro p
                      INNER JOIN Peligrosidad pg ON p.Peligrosidad_idPeligrosidad = pg.idPeligrosidad
                      WHERE p.idPerro IN ($idsStr)");

$niveles = [];
while ($fila = $conexion->registro()) {
    $niveles[] = strtoupper($fila[0]);
}
$conexion->cerrar();

$capacidadMap = ["BAJO" => 5, "MEDIO" => 3, "ALTO" => 2, "PELIGROSO" => 1];
$jerarquia = ["BAJO" => 1, "MEDIO" => 2, "ALTO" => 3, "PELIGROSO" => 4];

$totalCapacidad = 0;
$nivelMax = "BAJO";
$nivelMaxInt = 0;
foreach ($niveles as $n) {
    $totalCapacidad += $capacidadMap[$n];
    if ($jerarquia[$n] > $nivelMaxInt) {
        $nivelMaxInt = $jerarquia[$n];
        $nivelMax = $n;
    }
}

$capacidadMax = 6;
$nivelMaximoStr = ucfirst(strtolower($nivelMax));

if ($totalCapacidad > $capacidadMax) {
    $mensaje = "Estos perros exceden la capacidad máxima ($capacidadMax). Capacidad actual: $totalCapacidad.";
} else {
    $mensaje = "Capacidad suficiente. Capacidad actual: $totalCapacidad de $capacidadMax.";
}

$nivelMaxDisplay = $nivelMaximoStr;
if ($nivelMax === "PELIGROSO") {
    $nivelMaxDisplay = "Peligroso";
    $mensaje .= " Se requiere bozal.";
}

echo json_encode([
    "capacidadMax" => $capacidadMax,
    "nivelMaximo" => $nivelMaximoStr,
    "capacidadActual" => $totalCapacidad,
    "mensaje" => $mensaje
]);
