<?php
header('Content-Type: application/json');
require_once(__DIR__ . "/../logica/Paseo.php");
require_once(__DIR__ . "/../logica/Paseador.php");
require_once(__DIR__ . "/../persistencia/Conexion.php");

$idPerro1 = isset($_POST["idPerro1"]) ? (int)$_POST["idPerro1"] : 0;
$idPerro2 = isset($_POST["idPerro2"]) ? (int)$_POST["idPerro2"] : 0;
$idPaseador = isset($_POST["idPaseador"]) ? (int)$_POST["idPaseador"] : 0;
$fechaInicio = isset($_POST["fechaInicio"]) ? trim($_POST["fechaInicio"]) : "";
$fechaFin = isset($_POST["fechaFin"]) ? trim($_POST["fechaFin"]) : "";

if ($idPerro1 <= 0 || $idPaseador <= 0 || empty($fechaInicio)) {
    echo json_encode(["exito" => false, "mensaje" => "Faltan datos requeridos."]);
    exit;
}

$fechaInicioDT = new DateTime($fechaInicio);
$ahora = new DateTime();

$diff = $ahora->diff($fechaInicioDT);
$horasAnticipacion = ($diff->days * 24) + $diff->h + ($diff->i / 60);
if ($horasAnticipacion < 5) {
    echo json_encode(["exito" => false, "mensaje" => "Debes reservar con al menos 5 horas de anticipación."]);
    exit;
}

$perros = [$idPerro1];
if ($idPerro2 > 0) {
    $perros[] = $idPerro2;
}

$totalPerrosSolicitud = count($perros);
if ($totalPerrosSolicitud > 5) {
    echo json_encode(["exito" => false, "mensaje" => "Máximo 5 perros por paseo."]);
    exit;
}

$conexion = new Conexion();
$conexion->abrir();

$niveles = [];
$necesitaBozal = false;
foreach ($perros as $idPerro) {
    $conexion->ejecutar("SELECT p.idPerro, p.Nombre, pg.Nivel, r.idRaza
                          FROM Perro p
                          INNER JOIN Peligrosidad pg ON p.Peligrosidad_idPeligrosidad = pg.idPeligrosidad
                          INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza
                          WHERE p.idPerro = $idPerro");
    if ($fila = $conexion->registro()) {
        $nivel = strtoupper($fila[2]);
        $niveles[] = $nivel;
        if ($nivel === "PELIGROSO") {
            $necesitaBozal = true;
        }
    }
}

if ($necesitaBozal) {
    $conexion->ejecutar("SELECT AprobadoPeligroso FROM Paseador WHERE idPaseador = $idPaseador");
    $regPeli = $conexion->registro();
    if (!$regPeli || !$regPeli[0]) {
        $conexion->cerrar();
        echo json_encode(["exito" => false, "mensaje" => "El paseador seleccionado no está aprobado para manejar perros de nivel PELIGROSO."]);
        exit;
    }
}

$jerarquia = ["BAJO" => 1, "MEDIO" => 2, "ALTO" => 3, "PELIGROSO" => 4];
$limiteMap = ["BAJO" => 5, "MEDIO" => 3, "ALTO" => 2, "PELIGROSO" => 1];

$nivelMaximo = "BAJO";
$nivelMaxInt = 0;
foreach ($niveles as $n) {
    if (($jerarquia[$n] ?? 0) > $nivelMaxInt) {
        $nivelMaxInt = $jerarquia[$n];
        $nivelMaximo = $n;
    }
}

$limite = $limiteMap[$nivelMaximo];
if ($totalPerrosSolicitud > $limite) {
    $conexion->cerrar();
    $nivelStr = ucfirst(strtolower($nivelMaximo));
    echo json_encode(["exito" => false, "mensaje" => "Límite excedido: nivel $nivelStr permite máximo $limite perro(s), seleccionaste $totalPerrosSolicitud."]);
    exit;
}

if ($necesitaBozal) {
    $bozal = 0;
    if (isset($_POST["bozal"])) {
        $bozal = (int)$_POST["bozal"];
    }
    if ($bozal != 1) {
        $conexion->cerrar();
        echo json_encode(["exito" => false, "mensaje" => "Se requiere bozal para perros de nivel PELIGROSO."]);
        exit;
    }
}

$diaSemana = (int)$fechaInicioDT->format('N');
$horaStr = $fechaInicioDT->format('H:i:s');

$conexion->ejecutar("SELECT idDisponibilidad FROM disponibilidad
                      WHERE paseador_idPaseador = $idPaseador
                      AND DiaSemana_idDiaSemana = $diaSemana
                      AND HoraInicio <= '$horaStr'
                      AND HoraFin > '$horaStr'");
if ($conexion->filas() === 0) {
    $conexion->cerrar();
    echo json_encode(["exito" => false, "mensaje" => "El paseador no está disponible en ese horario."]);
    exit;
}

$fechaStr = $fechaInicioDT->format('Y-m-d');
$horaInicio = $fechaInicioDT->format('H:i:s');
$conexion->ejecutar("SELECT COUNT(*) FROM Paseo p
                      WHERE p.Paseador_idPaseador = $idPaseador
                      AND p.Estado_idEstado IN (1,2)
                      AND p.FechaInicio < '$fechaStr 23:59:59'
                      AND p.FechaFin > '$fechaStr $horaInicio'");
$fila = $conexion->registro();
if ($fila && $fila[0] >= 2) {
    $conexion->cerrar();
    echo json_encode(["exito" => false, "mensaje" => "El paseador ya tiene 2 paseos agendados en esa hora."]);
    exit;
}

$resultado = Paseo::validarCapacidadConcurrente($idPaseador, $fechaInicio, $perros, 0, true);
if (!$resultado["valido"]) {
    $conexion->cerrar();
    echo json_encode(["exito" => false, "mensaje" => $resultado["mensaje"]]);
    exit;
}

$bozalVal = $necesitaBozal ? 1 : 0;
$paseo = new Paseo(0, $fechaInicio, "", $idPaseador, 1, "", $idPerro1);
$paseo->setBozal($bozalVal);

$perroIds = [$idPerro1];
if ($idPerro2 > 0) {
    $perroIds[] = $idPerro2;
}

$exito = $paseo->insertar($perroIds);

$conexion->cerrar();

if ($exito) {
    echo json_encode(["exito" => true, "mensaje" => "Paseo solicitado correctamente."]);
} else {
    echo json_encode(["exito" => false, "mensaje" => "Error al solicitar el paseo."]);
}
