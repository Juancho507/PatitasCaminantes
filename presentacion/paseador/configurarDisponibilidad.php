<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>
<body>
<?php
include("presentacion/encabezadoP.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$diaSemana = new DiaSemana();
$dias = $diaSemana->consultarTodos();

$disponibilidad = new Disponibilidad();
$disponibilidades = $disponibilidad->consultarPorPaseador($id);

$dispPorDia = [];
foreach ($disponibilidades as $d) {
    $dispPorDia[$d->getIdDiaSemana()][] = $d;
}
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-calendar-week"></i> Configurar Disponibilidad</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Define tus horarios disponibles para cada d&iacute;a de la semana.</p>

                    <form id="formDisponibilidad" class="row g-3 mb-4 p-3 border rounded bg-light">
                        <div class="col-md-4">
                            <label class="form-label">D&iacute;a</label>
                            <select id="idDiaSemana" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($dias as $d): ?>
                                <option value="<?php echo $d->getId(); ?>"><?php echo $d->getNombre(); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hora Inicio</label>
                            <input type="time" id="horaInicio" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Hora Fin</label>
                            <input type="time" id="horaFin" class="form-control" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa-solid fa-plus"></i> Agregar
                            </button>
                        </div>
                    </form>

                    <div id="mensaje"></div>

                    <?php foreach ($dias as $d): ?>
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center
                            <?php echo ($d->getId() >= 6) ? 'bg-danger text-white' : 'bg-info text-white'; ?>">
                            <span><strong><?php echo $d->getNombre(); ?></strong></span>
                            <span class="badge bg-light text-dark">
                                <?php echo isset($dispPorDia[$d->getId()]) ? count($dispPorDia[$d->getId()]) : 0; ?> horario(s)
                            </span>
                        </div>
                        <div class="card-body">
                            <?php if (isset($dispPorDia[$d->getId()])): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th style="width:80px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-<?php echo $d->getId(); ?>">
                                        <?php foreach ($dispPorDia[$d->getId()] as $disp): ?>
                                        <tr id="disp-<?php echo $disp->getId(); ?>">
                                            <td><?php echo substr($disp->getHoraInicio(), 0, 5); ?></td>
                                            <td><?php echo substr($disp->getHoraFin(), 0, 5); ?></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm eliminar-disp"
                                                    data-id="<?php echo $disp->getId(); ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <p class="text-muted mb-0">Sin horarios registrados.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#formDisponibilidad").submit(function(e) {
        e.preventDefault();
        const data = {
            idPaseador: <?php echo $id; ?>,
            idDiaSemana: $("#idDiaSemana").val(),
            horaInicio: $("#horaInicio").val(),
            horaFin: $("#horaFin").val()
        };
        $.ajax({
            url: "ajax/guardarDisponibilidad.php",
            method: "POST",
            data: data,
            dataType: "json",
            success: function(res) {
                if (res.exito) {
                    $("#mensaje").html('<div class="alert alert-success alert-dismissible fade show">' +
                        res.mensaje +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    $("#mensaje").html('<div class="alert alert-danger alert-dismissible fade show">' +
                        res.mensaje +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }
            }
        });
    });

    $(document).on("click", ".eliminar-disp", function() {
        if (!confirm("¿Eliminar este horario?")) return;
        const btn = $(this);
        const id = btn.data("id");
        $.ajax({
            url: "ajax/eliminarDisponibilidad.php",
            method: "POST",
            data: { id: id },
            dataType: "json",
            success: function(res) {
                if (res.exito) {
                    $("#disp-" + id).fadeOut(300, function() { $(this).remove(); });
                } else {
                    alert(res.mensaje);
                }
            }
        });
    });
});
</script>
</body>
</html>
