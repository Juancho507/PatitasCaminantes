<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoP.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$paseo = new Paseo();
$paseos = $paseo->consultarHistorial("paseador", $id);

$estadoP = new EstadoPaseo();
$estadosPaseo = $estadoP->consultarTodos();
$paseosAgrupados = [];
foreach ($paseos as $p) {
    $idPaseo = $p->getId();
    
    if (!isset($paseosAgrupados[$idPaseo])) {
        $paseosAgrupados[$idPaseo] = new stdClass();
        $paseosAgrupados[$idPaseo]->id = $idPaseo;
        $paseosAgrupados[$idPaseo]->fecha = substr($p->getFechaInicio(), 0, 10);
        $paseosAgrupados[$idPaseo]->hora = substr($p->getFechaInicio(), 11, 5);
        $paseosAgrupados[$idPaseo]->estado = $p->getEstadoPaseo();
        $paseosAgrupados[$idPaseo]->perros = [$p->getNombrePerro()];
    } else {
        $paseosAgrupados[$idPaseo]->perros[] = $p->getNombrePerro();
    }
}
?>

<div class="container mt-4">
    <h2>Paseos</h2>

    <?php if (empty($paseosAgrupados)) { ?>
        <div class="alert alert-info">No tienes paseos registrados.</div>
    <?php } else { ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="table-danger">
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Perro(s)</th>
                    <th>Estado</th>
                    <th>Actualizar Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paseosAgrupados as $p): 
                    $uniqueElementId = $p->id;
                    ?>
                    <tr>
                        <td><?php echo $p->fecha; ?></td>
                        <td><?php echo $p->hora; ?></td>
                        <td><?php echo implode(", ", $p->perros); ?></td>
                        <td id="estadoTexto<?php echo $uniqueElementId; ?>"><?php echo $p->estado; ?></td>
                        <td>
                            <?php foreach ($estadosPaseo as $e) {
                                if ($e->getId() == 1) continue;
                                ?>
                                <button class="btn btn-outline-dark btn-sm me-1"
                                    id="btnEstado<?php echo $e->getValor() . $uniqueElementId; ?>">
                                    <?php echo $e->getValor(); ?>
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>
</div>

<script>
$(document).ready(function(){
<?php
foreach ($paseosAgrupados as $p) {
    $uniqueElementId = $p->id;
    foreach ($estadosPaseo as $e) {
        if ($e->getId() == 1) continue;

        echo '$("#btnEstado' . $e->getValor() . $uniqueElementId . '").click(function(){' . "\n";
        echo '  $.ajax({' . "\n";
        echo '    url: "ajax/actualizarEstadoPaseo.php",' . "\n";
        echo '    method: "GET",' . "\n";
        echo '    data: { id: "' . $uniqueElementId . '", estado: "' . $e->getId() . '" },' . "\n";
        echo '    success: function(response) {' . "\n";
        echo '      $("#estadoTexto' . $uniqueElementId . '").text("' . $e->getValor() . '");' . "\n";
        echo '    }' . "\n";
        echo '  });' . "\n";
        echo '});' . "\n";
    }
}
?>
});
</script>