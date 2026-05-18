<?php
header('Content-Type: application/json');

try {
    require_once(__DIR__ . "/../logica/Disponibilidad.php");
    require_once(__DIR__ . "/../logica/Paseador.php");
    require_once(__DIR__ . "/../persistencia/Conexion.php");

    $idPaseador = isset($_GET["idPaseador"]) ? (int)$_GET["idPaseador"] : 0;
    $mes = isset($_GET["mes"]) ? (int)$_GET["mes"] : (int)date('n');
    $año = isset($_GET["año"]) ? (int)$_GET["año"] : (int)date('Y');

    if ($idPaseador <= 0) {
        echo json_encode(["diasDisponibles" => [], "nombrePaseador" => ""]);
        exit;
    }

    $disp = new Disponibilidad();
    $disponibilidades = $disp->consultarPorPaseador($idPaseador);

    $paseador = new Paseador($idPaseador);
    $paseador->consultar();
    $nombrePaseador = $paseador->getNombre() . " " . $paseador->getApellido();

    $diasDisponibles = [];

    $ultimoDiaMes = (int)date('t', mktime(0, 0, 0, $mes, 1, $año));

    $siguienteMes = $mes + 1;
    $siguienteAño = $año;
    if ($siguienteMes > 12) {
        $siguienteMes = 1;
        $siguienteAño++;
    }
    $limiteDias = $ultimoDiaMes + 3;

    $ahora = new DateTime();

    $mapaDias = [
        "Monday" => 1, "Tuesday" => 2, "Wednesday" => 3, "Thursday" => 4,
        "Friday" => 5, "Saturday" => 6, "Sunday" => 7
    ];

    $diasTrabajados = [];
    foreach ($disponibilidades as $d) {
        $idDia = $d->getIdDiaSemana();
        $horaInicio = $d->getHoraInicio();
        $horaFin = $d->getHoraFin();
        if (!isset($diasTrabajados[$idDia])) {
            $diasTrabajados[$idDia] = ["inicio" => $horaInicio, "fin" => $horaFin];
        } else {
            if ($horaInicio < $diasTrabajados[$idDia]["inicio"]) {
                $diasTrabajados[$idDia]["inicio"] = $horaInicio;
            }
            if ($horaFin > $diasTrabajados[$idDia]["fin"]) {
                $diasTrabajados[$idDia]["fin"] = $horaFin;
            }
        }
    }

    $conexion = new Conexion();
    $conexion->abrir();

    for ($dia = 1; $dia <= $limiteDias; $dia++) {
        if ($dia <= $ultimoDiaMes) {
            $fechaStr = sprintf("%04d-%02d-%02d", $año, $mes, $dia);
        } else {
            $d = $dia - $ultimoDiaMes;
            $fechaStr = sprintf("%04d-%02d-%02d", $siguienteAño, $siguienteMes, $d);
        }

        $timestamp = strtotime($fechaStr);
        $diaSemanaNum = (int)date('N', $timestamp);
        $nombreDiaIngles = date('l', $timestamp);

        if (!isset($diasTrabajados[$diaSemanaNum])) {
            $diasDisponibles[$dia] = ["disponible" => false];
            continue;
        }

        $horaInicio = $diasTrabajados[$diaSemanaNum]["inicio"];
        $horaFin = $diasTrabajados[$diaSemanaNum]["fin"];
        $horaInicioInt = (int)explode(":", $horaInicio)[0];
        $horaFinInt = (int)explode(":", $horaFin)[0];

        $horas = [];

        $conexion->ejecutar("SELECT FechaInicio, FechaFin FROM paseo WHERE Paseador_idPaseador = $idPaseador AND EstadoPaseo_idEstadoPaseo IN (1,2) AND DATE(FechaInicio) = '$fechaStr'");
        $paseosExistentes = [];
        while ($fila = $conexion->registro()) {
            $paseosExistentes[] = ["inicio" => $fila[0], "fin" => $fila[1]];
        }

        for ($h = $horaInicioInt; $h < $horaFinInt; $h++) {
            $horaStr = sprintf("%02d:00", $h);
            $horaDateTime = new DateTime($fechaStr . " " . $horaStr);

            if ($horaDateTime <= $ahora) {
                continue;
            }

            $disponible = true;
            $horaFinStr = sprintf("%02d:00", $h + 1);
            foreach ($paseosExistentes as $pe) {
                $peInicio = new DateTime($pe["inicio"]);
                $peFin = new DateTime($pe["fin"]);
                if ($horaDateTime < $peFin && new DateTime($fechaStr . " " . $horaFinStr) > $peInicio) {
                    $disponible = false;
                    break;
                }
            }

            if ($disponible) {
                $horas[] = $horaStr;
            }
        }

        $diasDisponibles[$dia] = [
            "disponible" => !empty($horas),
            "horas" => $horas
        ];
    }

    $conexion->cerrar();

    echo json_encode([
        "diasDisponibles" => $diasDisponibles,
        "nombrePaseador" => $nombrePaseador
    ]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage(), "diasDisponibles" => [], "nombrePaseador" => ""]);
}
?>
