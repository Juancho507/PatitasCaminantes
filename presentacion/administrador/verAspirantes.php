<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

function rutaDocumento($path) {
    if (empty($path)) return "";
    $path = ltrim($path, "/");
    if (strpos($path, "documentos/") === 0) $path = substr($path, 11);
    return "documentos/" . $path;
}

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

$conexion->ejecutar("SELECT idPaseador, Nombre, Apellido, Correo, Contacto, Estado_idEstado
    FROM Paseador
    WHERE Estado_idEstado IN (2, 4)
    ORDER BY Nombre ASC");
$paseadores = [];
while ($reg = $conexion->registro()) {
    $paseadores[] = $reg;
}

$conexion->ejecutar("SELECT p.idPaseador, p.Nombre, p.Apellido, p.Correo, p.Contacto, p.Certificados, p.AprobadoPeligroso
    FROM Paseador p
    WHERE p.Estado_idEstado IN (2, 4)
    ORDER BY p.Nombre ASC");
$peligrosos = [];
while ($reg = $conexion->registro()) {
    $peligrosos[] = $reg;
}
$conexion->cerrar();
?>
<div class="container mt-4">
    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="aspirantes-tab" data-bs-toggle="tab" data-bs-target="#aspirantes" type="button" role="tab">
                <i class="fas fa-user-check me-1"></i>Aspirantes a Paseador
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gestion-tab" data-bs-toggle="tab" data-bs-target="#gestion" type="button" role="tab">
                <i class="fas fa-users-cog me-1"></i>Gestión de Paseadores
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="peligrosos-tab" data-bs-toggle="tab" data-bs-target="#peligrosos" type="button" role="tab">
                <i class="fas fa-shield-dog me-1"></i>Perros Peligrosos
            </button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        <div class="tab-pane fade show active" id="aspirantes" role="tabpanel">
            <h4 class="mb-3"><i class="fas fa-user-check me-2"></i>Aspirantes a Paseador</h4>
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
                                        data-hv="<?php echo htmlspecialchars(rutaDocumento($a[6] ?? '')); ?>"
                                        data-cert="<?php echo htmlspecialchars(rutaDocumento($a[7] ?? '')); ?>"
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
                                        <a href="<?php echo rutaDocumento($a[6]); ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="fas fa-file-pdf"></i> HV
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($a[7])): ?>
                                        <a href="<?php echo rutaDocumento($a[7]); ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
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

        <div class="tab-pane fade" id="gestion" role="tabpanel">
            <h4 class="mb-3"><i class="fas fa-users-cog me-2"></i>Gestión de Paseadores</h4>
            <div class="mb-3">
                <input type="text" id="filtroGestion" class="form-control" placeholder="Buscar por nombre, correo o contacto...">
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="tablaGestion">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($paseadores)): ?>
                            <tr><td colspan="5" class="text-center text-muted">No hay paseadores registrados.</td></tr>
                        <?php else: ?>
                            <?php foreach ($paseadores as $p): ?>
                            <tr>
                                <td class="td-nombre"><?php echo htmlspecialchars($p[1] . ' ' . $p[2]); ?></td>
                                <td class="td-correo"><?php echo htmlspecialchars($p[3]); ?></td>
                                <td class="td-contacto"><?php echo htmlspecialchars($p[4]); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $p[5] == 2 ? 'success' : 'danger'; ?> estado-paseador" id="estado-<?php echo $p[0]; ?>">
                                        <?php echo $p[5] == 2 ? 'Activo' : 'Inactivo'; ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-<?php echo $p[5] == 2 ? 'danger' : 'success'; ?> toggle-paseador"
                                        data-id="<?php echo $p[0]; ?>"
                                        data-estado="<?php echo $p[5]; ?>">
                                        <?php echo $p[5] == 2 ? 'Deshabilitar' : 'Habilitar'; ?>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="respuestaGestion" class="mt-3"></div>
        </div>

        <div class="tab-pane fade" id="peligrosos" role="tabpanel">
            <h4 class="mb-3"><i class="fas fa-shield-dog me-2"></i>Aprobar Paseadores para Perros Peligrosos</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Contacto</th>
                            <th>Certificado</th>
                            <th>Aprobado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tablaPeligrosos">
                        <?php if (empty($peligrosos)): ?>
                            <tr><td colspan="7" class="text-center text-muted">No hay paseadores activos.</td></tr>
                        <?php else: ?>
                            <?php foreach ($peligrosos as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p[1]) ?></td>
                                <td><?= htmlspecialchars($p[2]) ?></td>
                                <td><?= htmlspecialchars($p[3]) ?></td>
                                <td><?= htmlspecialchars($p[4]) ?></td>
                                <td>
                                    <?php if (!empty($p[5])): ?>
                                        <a href="documentos/<?= htmlspecialchars($p[5]) ?>" target="_blank" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> Ver</a>
                                    <?php else: ?>
                                        <span class="text-muted">Sin certificado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($p[6]): ?>
                                        <span class="badge bg-success">Sí</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm <?= $p[6] ? 'btn-danger' : 'btn-success' ?> btn-toggle-peligroso"
                                        data-id="<?= $p[0] ?>"
                                        data-estado="<?= $p[6] ? 0 : 1 ?>">
                                        <?= $p[6] ? 'Desactivar' : 'Activar' ?>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="respuestaPeligrosos" class="mt-3"></div>
        </div>
    </div>
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
            docsHtml += '<a href="' + hv + '" class="btn btn-outline-danger me-2" target="_blank"><i class="fas fa-file-pdf"></i> Hoja de Vida</a>';
        }
        if (cert) {
            docsHtml += '<a href="' + cert + '" class="btn btn-outline-danger" target="_blank"><i class="fas fa-file-pdf"></i> Certificados</a>';
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

    function filtrarGestion() {
        var filtro = $("#filtroGestion").val().toLowerCase().trim();
        var palabras = filtro.split(/\s+/);

        $("#tablaGestion tbody tr").each(function() {
            var fila = $(this);
            var nombre = fila.find(".td-nombre").text();
            var correo = fila.find(".td-correo").text();
            var contacto = fila.find(".td-contacto").text();
            var textoCompleto = (nombre + " " + correo + " " + contacto).toLowerCase();
            var coincide = palabras.every(function(p) { return textoCompleto.includes(p); });
            fila.toggle(coincide);
        });
    }

    $("#filtroGestion").on("input", filtrarGestion);

    $(document).on("click", ".toggle-paseador", function() {
        var btn = $(this);
        var id = btn.data("id");
        var estadoActual = btn.data("estado");
        var nuevoEstado = estadoActual == 2 ? 4 : 2;

        $.ajax({
            url: "ajax/paseadorEstadoAjax.php",
            method: "POST",
            data: { idPaseador: id, estado: nuevoEstado },
            dataType: "json",
            success: function(response) {
                if (response.exito) {
                    var fila = btn.closest("tr");
                    var estadoSpan = fila.find(".estado-paseador");

                    estadoSpan.text(nuevoEstado == 2 ? "Activo" : "Inactivo")
                        .removeClass("bg-success bg-danger")
                        .addClass(nuevoEstado == 2 ? "bg-success" : "bg-danger");

                    btn.text(nuevoEstado == 2 ? "Deshabilitar" : "Habilitar")
                        .removeClass("btn-success btn-danger")
                        .addClass(nuevoEstado == 2 ? "btn-danger" : "btn-success");

                    btn.data("estado", nuevoEstado);

                    $("#respuestaGestion").html('<div class="alert alert-success alert-dismissible fade show">Estado actualizado correctamente.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                } else {
                    $("#respuestaGestion").html('<div class="alert alert-danger">' + response.mensaje + '</div>');
                }
            },
            error: function() {
                $("#respuestaGestion").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });

    $(document).on("click", ".btn-toggle-peligroso", function() {
        var btn = $(this);
        var id = btn.data("id");
        var estado = btn.data("estado");
        var fila = btn.closest("tr");

        $.ajax({
            url: "ajax/gestionarCertificadoPeligroso.php",
            method: "POST",
            data: { accion: "toggle", id: id, estado: estado },
            dataType: "json",
            success: function(resp) {
                if (resp.exito) {
                    if (estado == 1) {
                        fila.find("td:eq(5)").html('<span class="badge bg-success">Sí</span>');
                        btn.text("Desactivar").removeClass("btn-success").addClass("btn-danger").data("estado", 0);
                    } else {
                        fila.find("td:eq(5)").html('<span class="badge bg-secondary">No</span>');
                        btn.text("Activar").removeClass("btn-danger").addClass("btn-success").data("estado", 1);
                    }
                    $("#respuestaPeligrosos").html('<div class="alert alert-success alert-dismissible fade show">Estado actualizado.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                } else {
                    $("#respuestaPeligrosos").html('<div class="alert alert-danger">' + resp.mensaje + '</div>');
                }
            },
            error: function() {
                $("#respuestaPeligrosos").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });
});
</script>
