<?php
$idDueño = $_SESSION["id"];
$rol = $_SESSION["rol"];
include("presentacion/encabezadoD.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$paseo = new Paseo();
$paseos = $paseo->consultarHistorial("dueño", $idDueño);

$completados = array_filter($paseos, function ($p) {
    return trim($p->getEstadoPaseo()) === 'completado';
});

$paseosAgrupados = [];
foreach ($completados as $p) {
    $id = $p->getId();
    if (!isset($paseosAgrupados[$id])) {
        $paseosAgrupados[$id] = new stdClass();
        $paseosAgrupados[$id]->id = $id;
        $paseosAgrupados[$id]->fechaInicio = $p->getFechaInicio();
        $paseosAgrupados[$id]->fechaFin = $p->getFechaFin();
        $paseosAgrupados[$id]->paseador = $p->getPaseador();
        $paseosAgrupados[$id]->perros = [$p->getNombrePerro()];
        $paseosAgrupados[$id]->precioHora = (float)$p->getPrecio();
    } else {
        $paseosAgrupados[$id]->perros[] = $p->getNombrePerro();
    }
}

$desde = $_GET["desde"] ?? "";
$hasta = $_GET["hasta"] ?? "";
?>

<div class="container mt-4">
    <h3 class="mb-4">Facturas</h3>

    <form method="get" class="row g-2 mb-4">
        <input type="hidden" name="pid" value="<?php echo base64_encode('presentacion/paseo/verFacturas.php'); ?>">
        <div class="col-auto">
            <label class="form-label">Desde</label>
            <input type="date" name="desde" class="form-control form-control-sm" value="<?php echo $desde; ?>">
        </div>
        <div class="col-auto">
            <label class="form-label">Hasta</label>
            <input type="date" name="hasta" class="form-control form-control-sm" value="<?php echo $hasta; ?>">
        </div>
        <div class="col-auto d-flex align-items-end">
            <button type="submit" class="btn btn-outline-dark btn-sm me-1">Filtrar</button>
            <a href="?pid=<?php echo base64_encode('presentacion/paseo/verFacturas.php'); ?>" class="btn btn-outline-secondary btn-sm">Limpiar</a>
        </div>
    </form>

    <?php if (empty($paseosAgrupados)) { ?>
        <div class="alert alert-warning">No hay facturas disponibles.</div>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Factura #</th>
                        <th>Fecha</th>
                        <th>Paseador</th>
                        <th>Perro(s)</th>
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
                        $fecha = $fi->format('d/m/Y');

                        if ($desde && $fi->format('Y-m-d') < $desde) continue;
                        if ($hasta && $fi->format('Y-m-d') > $hasta) continue;
                    ?>
                        <tr>
                            <td><?php echo str_pad($f->id, 5, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo $fecha; ?></td>
                            <td><?php echo htmlspecialchars($f->paseador); ?></td>
                            <td><?php echo htmlspecialchars(implode(', ', $f->perros)); ?></td>
                            <td class="text-center"><?php echo $horas; ?></td>
                            <td class="text-end">$<?php echo number_format($totalFactura, 0, '', '.'); ?></td>
                            <td class="text-center">
                                <a href="presentacion/paseo/generarFactura.php?idPaseo=<?php echo $f->id; ?>"
                                   target="_blank" class="btn btn-sm btn-danger">
                                    <i class="bi bi-filetype-pdf"></i> Descargar PDF
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
