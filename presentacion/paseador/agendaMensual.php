<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

$mes = isset($_GET["mes"]) ? (int)$_GET["mes"] : (int)date("m");
$anio = isset($_GET["anio"]) ? (int)$_GET["anio"] : (int)date("Y");
if ($mes < 1) { $mes = 12; $anio--; }
if ($mes > 12) { $mes = 1; $anio++; }

$primerDia = mktime(0, 0, 0, $mes, 1, $anio);
$diasEnMes = (int)date("t", $primerDia);
$diaSemanaInicio = (int)date("w", $primerDia);
$nombreMes = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

$paseo = new Paseo();
$todosPaseos = $paseo->consultarHistorial("paseador", $id);

$paseosPorDia = [];
$totalGanancias = 0;
foreach ($todosPaseos as $p) {
    $fecha = substr($p->getFechaInicio(), 0, 10);
    $ts = strtotime($fecha);
    if ((int)date("m", $ts) === $mes && (int)date("Y", $ts) === $anio) {
        $dia = (int)date("j", $ts);
        $item = [
            "id" => $p->getId(),
            "hora" => substr($p->getFechaInicio(), 11, 5),
            "estado" => $p->getEstadoPaseo(),
            "precio" => (float)$p->getPrecio(),
            "perros" => $p->getNombrePerro(),
            "dueno" => $p->getDueño()
        ];
        $paseosPorDia[$dia][] = $item;
        if ($p->getEstadoPaseo() === "completado") {
            $totalGanancias += (float)$p->getPrecio();
        }
    }
}
?>
<body>
<?php
include("presentacion/encabezadoP.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa-solid fa-calendar-alt"></i> Agenda Mensual</h4>
                    <div>
                        <a href="?pid=<?php echo base64_encode("presentacion/paseador/agendaMensual.php"); ?>&mes=<?php echo $mes - 1; ?>&anio=<?php echo $anio; ?>" class="btn btn-light btn-sm me-2">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                        <strong><?php echo $nombreMes[$mes] . " " . $anio; ?></strong>
                        <a href="?pid=<?php echo base64_encode("presentacion/paseador/agendaMensual.php"); ?>&mes=<?php echo $mes + 1; ?>&anio=<?php echo $anio; ?>" class="btn btn-light btn-sm ms-2">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="alert alert-success mb-0 text-center">
                                <strong>Total del mes:</strong> $<?php echo number_format($totalGanancias, 0, ',', '.'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Dom</th><th>Lun</th><th>Mar</th><th>Mi&eacute;</th><th>Jue</th><th>Vie</th><th>S&aacute;b</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $diaActual = 1;
                                $celdas = $diaSemanaInicio;
                                while ($diaActual <= $diasEnMes) {
                                    echo "<tr>";
                                    for ($c = 0; $c < 7; $c++) {
                                        if ($celdas > 0 && $diaActual === 1) {
                                            echo "<td></td>";
                                            $celdas--;
                                        } elseif ($diaActual <= $diasEnMes) {
                                            $tiene = isset($paseosPorDia[$diaActual]);
                                            $clase = $tiene ? "bg-warning bg-opacity-25 fw-bold" : "";
                                            echo "<td class='$clase' style='cursor:pointer; height:70px; vertical-align:top;'>";
                                            echo "<span class='small'>$diaActual</span>";
                                            if ($tiene) {
                                                $count = count($paseosPorDia[$diaActual]);
                                                echo "<br><span class='badge bg-warning text-dark mt-1'>$count paseo(s)</span>";
                                            }
                                            echo "</td>";
                                            $diaActual++;
                                        } else {
                                            echo "<td></td>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="detalleDia" class="mt-4" style="display:none;">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0" id="tituloDia"></h5>
                            </div>
                            <div class="card-body" id="contenidoDia"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const paseosData = <?php echo json_encode($paseosPorDia); ?>;

$(document).ready(function() {
    $("td:has(.badge)").click(function() {
        const dia = $(this).find("span:first").text().trim();
        const paseos = paseosData[dia];
        if (!paseos) return;

        $("#tituloDia").text("Paseos del " + dia + " de <?php echo $nombreMes[$mes] . " " . $anio; ?>");
        let html = '<table class="table table-sm table-hover"><thead><tr>' +
            '<th>Hora</th><th>Perro</th><th>Due&ntilde;o</th><th>Estado</th><th>Precio</th>' +
            '</tr></thead><tbody>';

        paseos.forEach(function(p) {
            html += '<tr>' +
                '<td>' + p.hora + '</td>' +
                '<td>' + p.perros + '</td>' +
                '<td>' + p.dueno + '</td>' +
                '<td>' + p.estado + '</td>' +
                '<td>$' + (p.precio ? Number(p.precio).toLocaleString('es-CO') : '0') + '</td>' +
                '</tr>';
        });

        html += '</tbody></table>';
        $("#contenidoDia").html(html);
        $("#detalleDia").slideDown();
    });
});
</script>
</body>
</html>
