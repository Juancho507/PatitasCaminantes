<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$idPerro = $_GET["idPerro"] ?? 0;

include("presentacion/encabezado" . ($rol === "dueño" ? "D" : ($rol === "paseador" ? "P" : "A")) . ".php");
include("presentacion/menu" . ucfirst($rol) . ".php");

if ($rol === "dueño") {
    $perro = new Perro($idPerro);
    $perroInfo = $perro->consultarPerroPorId($idPerro);
    $titulo = $perroInfo ? "Facturas - " . htmlspecialchars($perroInfo->getNombre()) : "Facturas";

    $paseo = new Paseo();
    $paseos = $paseo->consultarHistorial("dueño", $id);
    $filtrados = array_filter($paseos, function ($p) use ($idPerro) {
        return $p->getIdPerro() == $idPerro && trim($p->getEstadoPaseo()) === 'completado';
    });
} else {
    $titulo = "Facturas - Mis Paseos Completados";
    $paseo = new Paseo();
    $paseos = $paseo->consultarHistorial("paseador", $id);
    $filtrados = array_filter($paseos, function ($p) {
        return trim($p->getEstadoPaseo()) === 'completado';
    });
}

$paseosAgrupados = [];
foreach ($filtrados as $p) {
    $pid = $p->getId();
    if (!isset($paseosAgrupados[$pid])) {
        $paseosAgrupados[$pid] = new stdClass();
        $paseosAgrupados[$pid]->id = $pid;
        $paseosAgrupados[$pid]->fechaInicio = $p->getFechaInicio();
        $paseosAgrupados[$pid]->fechaFin = $p->getFechaFin();
        $paseosAgrupados[$pid]->perros = [$p->getNombrePerro()];
        $paseosAgrupados[$pid]->precioHora = (float)$p->getPrecio();
        if ($rol === "dueño") {
            $paseosAgrupados[$pid]->paseador = $p->getPaseador();
        } else {
            $paseosAgrupados[$pid]->dueno = $p->getDueño();
        }
    } else {
        $paseosAgrupados[$pid]->perros[] = $p->getNombrePerro();
    }
}
?>

<div class="container mt-4">
    <h3 class="mb-4"><?php echo $titulo; ?></h3>

    <?php if (empty($paseosAgrupados)) { ?>
        <div class="alert alert-info">No hay paseos completados para facturar.</div>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Factura #</th>
                        <th>Fecha</th>
                        <?php if ($rol === "paseador"): ?><th>Dueño</th><?php endif; ?>
                        <th>Perro(s)</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Horas</th>
                        <th>Total</th>
                        <th>PDF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paseosAgrupados as $f):
                        $fi = new DateTime($f->fechaInicio);
                        $ff = new DateTime($f->fechaFin);
                        $diff = $fi->diff($ff);
                        $minutos = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
                        $horas = max(1, ceil($minutos / 60));
                        $totalFactura = $horas * $f->precioHora;
                    ?>
                        <tr>
                            <td><?php echo str_pad($f->id, 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo $fi->format('d/m/Y'); ?></td>
                            <?php if ($rol === "paseador"): ?>
                                <td><?php echo htmlspecialchars($f->dueno ?? 'N/A'); ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars(implode(', ', $f->perros)); ?></td>
                            <td><?php echo $fi->format('H:i'); ?></td>
                            <td><?php echo $ff->format('H:i'); ?></td>
                            <td class="text-center"><?php echo $horas; ?></td>
                            <td class="text-end">$<?php echo number_format($totalFactura, 0, '', '.'); ?></td>
                            <td class="text-center">
                                <a href="presentacion/paseo/generarFactura.php?idPaseo=<?php echo $f->id; ?>"
                                   target="_blank" class="btn btn-sm btn-danger">
                                    <i class="bi bi-filetype-pdf"></i> PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
