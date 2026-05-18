<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$conexion = new Conexion();
$conexion->abrir();
$conexion->ejecutar("SELECT idPaseador, Nombre, Apellido, Correo, NroDocumento, Estado_idEstado, HojaDeVida, Certificados, Informacion, FechaNacimiento
    FROM Paseador
    WHERE Estado_idEstado IN (1, 3)
    ORDER BY Estado_idEstado ASC, idPaseador DESC");
$aspirantes = [];
while ($reg = $conexion->registro()) {
    $aspirantes[] = $reg;
}
$conexion->cerrar();
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-user-check me-2"></i>Ver Aspirantes a Paseador</h2>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Documento</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($aspirantes)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No hay aspirantes pendientes.</td></tr>
                <?php else: ?>
                    <?php foreach ($aspirantes as $a): ?>
                    <tr id="aspirante-<?php echo $a[0]; ?>">
                        <td><?php echo htmlspecialchars($a[1]); ?></td>
                        <td><?php echo htmlspecialchars($a[2]); ?></td>
                        <td><?php echo htmlspecialchars($a[3]); ?></td>
                        <td><?php echo htmlspecialchars($a[4] ?? 'N/A'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $a[5] == 1 ? 'warning text-dark' : 'danger'; ?>">
                                <?php echo $a[5] == 1 ? 'Pendiente' : 'Rechazado'; ?>
                            </span>
                        </td>
                        <td><?php echo !empty($a[9]) ? date('d/m/Y', strtotime($a[9])) : 'N/A'; ?></td>
                        <td>
                            <button class="btn btn-sm btn-info btn-ver-aspirante" data-id="<?php echo $a[0]; ?>"
                                data-hv="<?php echo htmlspecialchars($a[6] ?? ''); ?>"
                                data-cert="<?php echo htmlspecialchars($a[7] ?? ''); ?>"
                                data-info="<?php echo htmlspecialchars($a[8] ?? ''); ?>"
                                data-nombre="<?php echo htmlspecialchars($a[1] . ' ' . $a[2]); ?>">
                                <i class="fas fa-eye"></i> Ver
                            </button>
                            <?php if ($a[5] == 1): ?>
                                <button class="btn btn-sm btn-success btn-aprobar-aspirante" data-id="<?php echo $a[0]; ?>">
                                    <i class="fas fa-check"></i> Aprobar
                                </button>
                                <button class="btn btn-sm btn-danger btn-rechazar-aspirante" data-id="<?php echo $a[0]; ?>">
                                    <i class="fas fa-times"></i> Rechazar
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-success btn-aprobar-aspirante" data-id="<?php echo $a[0]; ?>">
                                    <i class="fas fa-undo"></i> Reaprobar
                                </button>
                            <?php endif; ?>
                            <?php if (!empty($a[6])): ?>
                                <a href="documentos/<?php echo htmlspecialchars($a[6]); ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="fas fa-file-pdf"></i> HV
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($a[7])): ?>
                                <a href="documentos/<?php echo htmlspecialchars($a[7]); ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Cert
                                </a>
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

<div class="modal fade" id="modalVerAspirante" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Aspirante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 id="modalNombreAspirante" class="mb-3"></h6>
                <p><strong>Información:</strong></p>
                <p id="modalInfoAspirante" class="text-muted"></p>
                <hr>
                <p><strong>Documentos:</strong></p>
                <div id="modalDocumentosAspirante"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on("click", ".btn-ver-aspirante", function() {
        var info = $(this).data("info") || "Sin información adicional.";
        var nombre = $(this).data("nombre");
        var hv = $(this).data("hv");
        var cert = $(this).data("cert");

        $("#modalNombreAspirante").text(nombre);
        $("#modalInfoAspirante").text(info);

        var docsHtml = "";
        if (hv) {
            docsHtml += '<a href="documentos/' + hv + '" class="btn btn-outline-danger me-2" target="_blank"><i class="fas fa-file-pdf"></i> Hoja de Vida</a>';
        }
        if (cert) {
            docsHtml += '<a href="documentos/' + cert + '" class="btn btn-outline-danger" target="_blank"><i class="fas fa-file-pdf"></i> Certificados</a>';
        }
        if (!hv && !cert) {
            docsHtml = '<span class="text-muted">No hay documentos disponibles.</span>';
        }
        $("#modalDocumentosAspirante").html(docsHtml);

        var modal = new bootstrap.Modal(document.getElementById("modalVerAspirante"));
        modal.show();
    });

    $(document).on("click", ".btn-aprobar-aspirante", function() {
        var btn = $(this);
        var id = btn.data("id");

        if (!confirm("¿Aprobar este aspirante como paseador?")) return;

        $.ajax({
            url: "ajax/gestionarAspirante.php",
            method: "POST",
            data: { id: id, accion: "aprobar" },
            success: function(response) {
                if (response.trim() === "ok") {
                    var fila = $("#aspirante-" + id);
                    fila.find(".badge").removeClass("bg-warning text-dark bg-danger").addClass("bg-success").text("Aprobado");
                    fila.find(".btn-aprobar-aspirante, .btn-rechazar-aspirante").remove();
                    fila.find("td:eq(6)").prepend('<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Aprobado</span>');
                    $("#respuestaAjax").html('<div class="alert alert-success alert-dismissible fade show">Aspirante aprobado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                } else {
                    $("#respuestaAjax").html('<div class="alert alert-danger">Error al aprobar.</div>');
                }
            },
            error: function() {
                $("#respuestaAjax").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });

    $(document).on("click", ".btn-rechazar-aspirante", function() {
        var btn = $(this);
        var id = btn.data("id");

        if (!confirm("¿Rechazar este aspirante?")) return;

        $.ajax({
            url: "ajax/gestionarAspirante.php",
            method: "POST",
            data: { id: id, accion: "rechazar" },
            success: function(response) {
                if (response.trim() === "ok") {
                    var fila = $("#aspirante-" + id);
                    fila.find(".badge").removeClass("bg-warning text-dark bg-danger").addClass("bg-danger").text("Rechazado");
                    fila.find(".btn-aprobar-aspirante, .btn-rechazar-aspirante").remove();
                    fila.find("td:eq(6)").prepend('<span class="text-danger fw-bold"><i class="fas fa-times-circle"></i> Rechazado</span>');
                    $("#respuestaAjax").html('<div class="alert alert-success alert-dismissible fade show">Aspirante rechazado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
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
