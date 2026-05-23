<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../persistencia/Conexion.php");

$idPerros = isset($_GET["idPerros"]) ? trim($_GET["idPerros"]) : "";

if (empty($idPerros)) {
    echo json_encode(["valido" => true, "totalPerros" => 0, "limite" => 5, "nivelMaximo" => "Bajo", "mensaje" => "Sin perros seleccionados."]);
    exit;
}

$ids = array_map('intval', explode(",", $idPerros));
$ids = array_filter($ids, function($v) { return $v > 0; });

if (empty($ids)) {
    echo json_encode(["valido" => true, "totalPerros" => 0, "limite" => 5, "nivelMaximo" => "Bajo", "mensaje" => "Sin perros seleccionados."]);
    exit;
}

$totalPerros = count($ids);
if ($totalPerros > 5) {
    echo json_encode(["valido" => false, "totalPerros" => $totalPerros, "limite" => 5, "nivelMaximo" => "Bajo", "mensaje" => "Máximo 5 perros por paseo."]);
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

$jerarquia = ["BAJO" => 1, "MEDIO" => 2, "ALTO" => 3, "PELIGROSO" => 4];
$limiteMap = ["BAJO" => 5, "MEDIO" => 3, "ALTO" => 2, "PELIGROSO" => 1];

$nivelMax = "BAJO";
$nivelMaxInt = 0;
foreach ($niveles as $n) {
    if (($jerarquia[$n] ?? 0) > $nivelMaxInt) {
        $nivelMaxInt = $jerarquia[$n];
        $nivelMax = $n;
    }
}

$limite = $limiteMap[$nivelMax];
$valido = $totalPerros <= $limite;
$nivelMaxStr = ucfirst(strtolower($nivelMax));

if ($valido) {
    $mensaje = "Capacidad suficiente. Nivel más alto: $nivelMaxStr ($totalPerros de $limite perros).";
} else {
    $mensaje = "Excede el límite. Nivel más alto: $nivelMaxStr (máx. $limite perros, seleccionaste $totalPerros).";
}

if ($nivelMax === "PELIGROSO") {
    $mensaje .= " Se requiere bozal.";
}

echo json_encode([
    "valido" => $valido,
    "totalPerros" => $totalPerros,
    "limite" => $limite,
    "nivelMaximo" => $nivelMaxStr,
    "mensaje" => $mensaje
]);
