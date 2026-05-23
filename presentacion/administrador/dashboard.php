<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$conexion = new Conexion();

$conexion->abrir();
$conexion->ejecutar("SELECT COUNT(*) FROM Dueño");
$totalDueños = $conexion->registro()[0];

$conexion->ejecutar("SELECT COUNT(*) FROM Paseador");
$totalPaseadores = $conexion->registro()[0];

$conexion->ejecutar("SELECT COUNT(*) FROM Perro");
$totalPerros = $conexion->registro()[0];

$conexion->ejecutar("SELECT COUNT(*) FROM Paseo WHERE DATE(FechaInicio) = CURDATE()");
$paseosHoy = $conexion->registro()[0];

$conexion->ejecutar("SELECT DATE(p.FechaInicio) AS fecha, SUM(t.PrecioHora) AS ingreso
    FROM Paseo p
    INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
    INNER JOIN Perro per ON p.perro_idPerro = per.idPerro
    INNER JOIN Tarifa t ON t.Paseador_idPaseador = pas.idPaseador AND t.Peligrosidad_idPeligrosidad = per.Peligrosidad_idPeligrosidad
    WHERE p.Estado_idEstado = 6
    AND p.FechaInicio >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(p.FechaInicio)
    ORDER BY fecha ASC");
$ingresos = [];
while ($reg = $conexion->registro()) {
    $ingresos[] = $reg;
}

$conexion->ejecutar("SELECT per.Nombre, COUNT(*) AS total
    FROM Paseo p
    INNER JOIN Perro per ON p.perro_idPerro = per.idPerro
    GROUP BY per.idPerro
    ORDER BY total DESC
    LIMIT 5");
$perrosPaseados = [];
while ($reg = $conexion->registro()) {
    $perrosPaseados[] = $reg;
}

$conexion->ejecutar("SELECT r.Raza, COUNT(*) AS total
    FROM Perro p
    INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza
    GROUP BY r.Raza
    ORDER BY total DESC
    LIMIT 5");
$razasComunes = [];
while ($reg = $conexion->registro()) {
    $razasComunes[] = $reg;
}

$conexion->ejecutar("SELECT p.idPaseo, p.FechaInicio,
    CONCAT(pas.Nombre, ' ', pas.Apellido) AS paseador,
    CONCAT(d.Nombre, ' ', d.Apellido) AS dueno,
    ep.Nombre
    FROM Paseo p
    INNER JOIN Paseador pas ON p.Paseador_idPaseador = pas.idPaseador
    INNER JOIN Perro per ON p.perro_idPerro = per.idPerro
    INNER JOIN Dueño d ON per.Dueño_idDueño = d.idDueño
    INNER JOIN estado ep ON p.Estado_idEstado = ep.idEstado
    ORDER BY p.FechaInicio DESC
    LIMIT 10");
$actividadReciente = [];
while ($reg = $conexion->registro()) {
    $actividadReciente[] = $reg;
}

$conexion->cerrar();
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Dashboard</h2>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-bg-primary h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <h3 class="card-title mb-0"><?php echo $totalDueños; ?></h3>
                    <p class="card-text">Total Dueños</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-bg-success h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-walking fa-2x mb-2"></i>
                    <h3 class="card-title mb-0"><?php echo $totalPaseadores; ?></h3>
                    <p class="card-text">Total Paseadores</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-bg-warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-dog fa-2x mb-2"></i>
                    <h3 class="card-title mb-0"><?php echo $totalPerros; ?></h3>
                    <p class="card-text">Total Perros</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-bg-info h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-day fa-2x mb-2"></i>
                    <h3 class="card-title mb-0"><?php echo $paseosHoy; ?></h3>
                    <p class="card-text">Paseos Hoy</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Ingresos Últimos 30 Días</h5></div>
                <div class="card-body">
                    <div id="chartIngresos" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-dog me-2"></i>Perros Más Paseados</h5></div>
                <div class="card-body">
                    <?php if (empty($perrosPaseados)): ?>
                        <p class="text-muted mb-0">Sin datos</p>
                    <?php else: ?>
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <?php foreach ($perrosPaseados as $i => $row): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?>.</td>
                                        <td><?php echo htmlspecialchars($row[0]); ?></td>
                                        <td class="text-end fw-bold"><?php echo $row[1]; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-paw me-2"></i>Razas Comunes</h5></div>
                <div class="card-body">
                    <?php if (empty($razasComunes)): ?>
                        <p class="text-muted mb-0">Sin datos</p>
                    <?php else: ?>
                        <div id="chartRazas" style="height: 200px;"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-history me-2"></i>Actividad Reciente</h5></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Paseador</th>
                            <th>Dueño</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($actividadReciente)): ?>
                            <tr><td colspan="4" class="text-center text-muted">Sin actividad</td></tr>
                        <?php else: ?>
                            <?php foreach ($actividadReciente as $row): ?>
                                <tr>
                                    <td><?php echo date("d/m/Y H:i", strtotime($row[1])); ?></td>
                                    <td><?php echo htmlspecialchars($row[2]); ?></td>
                                    <td><?php echo htmlspecialchars($row[3]); ?></td>
                                    <td>
                                        <?php
                                        $badge = match ($row[4]) {
                                            'Completado' => 'bg-success',
                                            'En espera' => 'bg-secondary',
                                            'Aceptado' => 'bg-primary',
                                            'En curso' => 'bg-warning text-dark',
                                            default => 'bg-danger'
                                        };
                                        ?>
                                        <span class="badge <?php echo $badge; ?>"><?php echo $row[4]; ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
google.charts.load('current', { packages: ['corechart', 'bar'] });
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    var dataIngresos = new google.visualization.DataTable();
    dataIngresos.addColumn('string', 'Fecha');
    dataIngresos.addColumn('number', 'Ingreso');
    dataIngresos.addRows([
        <?php
        if (!empty($ingresos)) {
            foreach ($ingresos as $row) {
                echo "['" . $row[0] . "', " . ($row[1] ?? 0) . "],\n";
            }
        } else {
            echo "['Sin datos', 0],\n";
        }
        ?>
    ]);

    var optionsIngresos = {
        chartArea: { width: '90%', height: '80%' },
        hAxis: { textPosition: 'none' },
        legend: { position: 'none' },
        colors: ['#0d6efd'],
        bar: { groupWidth: '90%' }
    };

    var chartIngresos = new google.visualization.ColumnChart(document.getElementById('chartIngresos'));
    chartIngresos.draw(dataIngresos, optionsIngresos);

    <?php if (!empty($razasComunes)): ?>
    var dataRazas = new google.visualization.DataTable();
    dataRazas.addColumn('string', 'Raza');
    dataRazas.addColumn('number', 'Cantidad');
    dataRazas.addRows([
        <?php foreach ($razasComunes as $row): ?>
        ['<?php echo htmlspecialchars($row[0], ENT_QUOTES); ?>', <?php echo $row[1]; ?>],
        <?php endforeach; ?>
    ]);

    var optionsRazas = {
        chartArea: { width: '90%', height: '85%' },
        legend: { position: 'none' },
        colors: ['#198754'],
        hAxis: { textPosition: 'none' }
    };

    var chartRazas = new google.visualization.ColumnChart(document.getElementById('chartRazas'));
    chartRazas.draw(dataRazas, optionsRazas);
    <?php endif; ?>
}
</script>
