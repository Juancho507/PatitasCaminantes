<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$conexion = new Conexion();
$conexion->abrir();
$conexion->ejecutar("SELECT s.idSolicitud, s.NombreRaza, CONCAT(d.Nombre, ' ', d.Apellido) AS Dueño, s.FechaSolicitud, es.Nombre
    FROM solicitudraza s
    INNER JOIN Dueño d ON s.idDueño = d.idDueño
    INNER JOIN estado es ON s.Estado_idEstado = es.idEstado
    ORDER BY FIELD(es.idEstado, 1, 2, 3), s.FechaSolicitud DESC");
$solicitudes = [];
while ($reg = $conexion->registro()) {
    $solicitudes[] = $reg;
}
$conexion->ejecutar("SELECT idTamaño, Tamaño FROM tamaño");
$tamaños = [];
while ($reg = $conexion->registro()) {
    $tamaños[] = $reg;
}
$conexion->cerrar();
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-paw me-2"></i>Solicitudes de Nueva Raza</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre Raza</th>
                    <th>Dueño</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($solicitudes)): ?>
                    <tr><td colspan="5" class="text-center text-muted">No hay solicitudes de raza.</td></tr>
                <?php else: ?>
                    <?php foreach ($solicitudes as $s): ?>
                    <tr id="solicitud-<?php echo $s[0]; ?>">
                        <td><strong><?php echo htmlspecialchars($s[1]); ?></strong></td>
                        <td><?php echo htmlspecialchars($s[2]); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($s[3])); ?></td>
                        <td>
                            <?php
                            $badgeClase = match ($s[4]) {
                                'aprobado' => 'bg-success',
                                'rechazado' => 'bg-danger',
                                default => 'bg-warning text-dark'
                            };
                            ?>
                            <span class="badge <?php echo $badgeClase; ?> estado-badge"><?php echo ucfirst($s[4]); ?></span>
                        </td>
                        <td class="acciones-solicitud">
                            <?php if ($s[4] === 'pendiente'): ?>
                                <select class="form-select form-select-sm d-inline-block w-auto tamaño-select" data-id="<?php echo $s[0]; ?>">
                                    <option value="">Seleccionar tamaño</option>
                                    <?php foreach ($tamaños as $t): ?>
                                    <option value="<?php echo $t[0]; ?>"><?php echo htmlspecialchars($t[1]); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-sm btn-success btn-aprobar-raza" data-id="<?php echo $s[0]; ?>">
                                    <i class="fas fa-check"></i> Aprobar
                                </button>
                                <button class="btn btn-sm btn-danger btn-rechazar-raza" data-id="<?php echo $s[0]; ?>">
                                    <i class="fas fa-times"></i> Rechazar
                                </button>
                            <?php else: ?>
                                <span class="text-muted">Sin acciones</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div id="respuestaAjax" class="mt-3"></div>
</div>

<script>
$(document).ready(function() {
    $(document).on("click", ".btn-aprobar-raza", function() {
        var btn = $(this);
        var id = btn.data("id");
        var tamaño = $(".tamaño-select[data-id='" + id + "']").val();

        if (!tamaño) {
            alert("Debes seleccionar un tamaño para la raza antes de aprobar.");
            return;
        }

        if (!confirm("¿Aprobar esta raza? Se creará " + $(".tamaño-select[data-id='" + id + "'] option:selected").text() + ": Hembra y Macho.")) return;

        $.ajax({
            url: "ajax/gestionarSolicitudRaza.php",
            method: "POST",
            data: { id: id, accion: "aprobar", tamaño: tamaño },
            success: function(response) {
                if (response.trim() === "ok") {
                    var fila = $("#solicitud-" + id);
                    fila.find(".estado-badge").removeClass("bg-warning text-dark").addClass("bg-success").text("Aprobado");
                    fila.find(".acciones-solicitud").html('<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Aprobado</span>');
                    $("#respuestaAjax").html('<div class="alert alert-success alert-dismissible fade show">Raza aprobada. Se crearon Hembra y Masculino.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                } else if (response.trim() === "duplicate") {
                    alert("ADVERTENCIA: Esta raza ya existe en el sistema. No se puede duplicar.");
                } else if (response.trim() === "notamaño") {
                    alert("Debes seleccionar un tamaño.");
                } else {
                    $("#respuestaAjax").html('<div class="alert alert-danger">Error al aprobar la solicitud.</div>');
                }
            },
            error: function() {
                $("#respuestaAjax").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });

    $(document).on("click", ".btn-rechazar-raza", function() {
        var btn = $(this);
        var id = btn.data("id");

        if (!confirm("¿Rechazar esta solicitud de raza?")) return;

        $.ajax({
            url: "ajax/gestionarSolicitudRaza.php",
            method: "POST",
            data: { id: id, accion: "rechazar" },
            success: function(response) {
                if (response.trim() === "ok") {
                    var fila = $("#solicitud-" + id);
                    fila.find(".estado-badge").removeClass("bg-warning text-dark").addClass("bg-danger").text("Rechazado");
                    fila.find(".acciones-solicitud").html('<span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> Rechazado</span>');
                    $("#respuestaAjax").html('<div class="alert alert-success alert-dismissible fade show">Solicitud rechazada.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                } else {
                    $("#respuestaAjax").html('<div class="alert alert-danger">Error al rechazar.</div>');
                }
            },
            error: function() {
                $("#respuestaAjax").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });
});
</script>
