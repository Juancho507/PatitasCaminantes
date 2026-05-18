<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoP.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$paseo = new Paseo();
$paseos = $paseo->consultarPendientesPorPaseador($id);

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
        $paseosAgrupados[$idPaseo]->observaciones = "";
    } else {
        $paseosAgrupados[$idPaseo]->perros[] = $p->getNombrePerro();
    }
}

$conexion = new Conexion();
$conexion->abrir();

foreach ($paseosAgrupados as $pid => $p) {
    $conexion->ejecutar("SELECT DISTINCT due.Nombre, due.Apellido, p.Observaciones
        FROM Paseo p
        JOIN Perro per ON p.perro_idPerro = per.idPerro
        JOIN Dueño due ON per.Dueño_idDueño = due.idDueño
        WHERE p.idPaseo = $pid");
    if ($row = $conexion->registro()) {
        $paseosAgrupados[$pid]->dueno = $row[0] . " " . $row[1];
        $paseosAgrupados[$pid]->observaciones = $row[2] ?? "";
    }
}

$paseosAceptados = [];
$conexion->ejecutar("SELECT p.idPaseo, p.FechaInicio, p.FechaFin,
    CONCAT_WS(', ', per1.Nombre, per2.Nombre, per3.Nombre, per4.Nombre, per5.Nombre, per6.Nombre) AS perros,
    CONCAT(due.Nombre, ' ', due.Apellido) AS dueno, p.Observaciones
    FROM Paseo p
    LEFT JOIN Perro per1 ON p.perro_idPerro = per1.idPerro
    LEFT JOIN Perro per2 ON p.perro_idPerro2 = per2.idPerro
    LEFT JOIN Perro per3 ON p.perro_idPerro3 = per3.idPerro
    LEFT JOIN Perro per4 ON p.perro_idPerro4 = per4.idPerro
    LEFT JOIN Perro per5 ON p.perro_idPerro5 = per5.idPerro
    LEFT JOIN Perro per6 ON p.perro_idPerro6 = per6.idPerro
    JOIN Dueño due ON per1.Dueño_idDueño = due.idDueño
    WHERE p.Paseador_idPaseador = $id AND p.EstadoPaseo_idEstadoPaseo = 2
    ORDER BY p.FechaInicio ASC");
while ($row = $conexion->registro()) {
    $paseosAceptados[] = [
        "id" => $row[0],
        "fecha" => substr($row[1], 0, 10),
        "hora" => substr($row[1], 11, 5),
        "perros" => $row[3],
        "dueno" => $row[4],
        "observaciones" => $row[5] ?? ""
    ];
}

$conexion->cerrar();
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h4 class="mb-0"><i class="fa-solid fa-clock"></i> Paseos Pendientes</h4>
                </div>
                <div class="card-body">
                    <div id="notificacionPaseo"></div>

                    <?php if (empty($paseosAgrupados)) { ?>
                        <div class="alert alert-info">No tienes paseos pendientes por revisar.</div>
                    <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Perro(s)</th>
                                    <th>Due&ntilde;o</th>
                                    <th>Observaciones</th>
                                    <th>Acci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paseosAgrupados as $p): ?>
                                <tr id="fila-<?php echo $p->id; ?>">
                                    <td><?php echo $p->fecha; ?></td>
                                    <td><?php echo $p->hora; ?></td>
                                    <td><?php echo implode(", ", $p->perros); ?></td>
                                    <td><?php echo $p->dueno ?? ""; ?></td>
                                    <td><?php echo $p->observaciones ?: "Ninguna"; ?></td>
                                    <td>
                                        <button class="btn btn-success btn-sm aceptar-btn" data-id="<?php echo $p->id; ?>">
                                            <i class="fa-solid fa-check"></i> Aceptar
                                        </button>
                                        <button class="btn btn-danger btn-sm rechazar-btn" data-id="<?php echo $p->id; ?>">
                                            <i class="fa-solid fa-times"></i> Rechazar
                                        </button>
                                        <div class="mt-1 motivo-box" id="motivo-<?php echo $p->id; ?>" style="display:none;">
                                            <input type="text" class="form-control form-control-sm motivo-input" placeholder="Motivo del rechazo">
                                            <button class="btn btn-outline-danger btn-sm mt-1 confirmar-rechazo" data-id="<?php echo $p->id; ?>">Confirmar</button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <?php if (!empty($paseosAceptados)): ?>
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-check-circle"></i> Paseos Aceptados</h4>
                </div>
                <div class="card-body">
                    <div id="notificacionAceptados"></div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Perro(s)</th>
                                    <th>Due&ntilde;o</th>
                                    <th>Observaciones</th>
                                    <th>Acci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paseosAceptados as $p): ?>
                                <tr id="fila-aceptado-<?php echo $p["id"]; ?>">
                                    <td><?php echo $p["fecha"]; ?></td>
                                    <td><?php echo $p["hora"]; ?></td>
                                    <td><?php echo $p["perros"]; ?></td>
                                    <td><?php echo $p["dueno"]; ?></td>
                                    <td><?php echo $p["observaciones"] ?: "Ninguna"; ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-sm completar-btn" data-id="<?php echo $p["id"]; ?>">
                                            <i class="fa-solid fa-flag-checkered"></i> Completar Paseo
                                        </button>
                                        <button class="btn btn-danger btn-sm cancelar-paseador-btn" data-id="<?php echo $p["id"]; ?>">
                                            <i class="fa-solid fa-ban"></i> Cancelar Paseo
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function mostrarNotificacion(tipo, mensaje) {
        const alerta = '<div class="alert alert-' + tipo + ' alert-dismissible fade show">' +
            mensaje + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        $("#notificacionPaseo").html(alerta);
        setTimeout(function() { $(".alert").alert("close"); }, 5000);
    }

    $(document).on("click", ".aceptar-btn", function() {
        const btn = $(this);
        const id = btn.data("id");
        btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span>');
        $.ajax({
            url: "ajax/aceptarRechazarPaseo.php",
            method: "POST",
            data: { idPaseo: id, accion: "aceptar" },
            dataType: "json",
            success: function(res) {
                if (res.exito) {
                    $("#fila-" + id).fadeOut(300);
                    mostrarNotificacion("success", res.mensaje);
                } else {
                    mostrarNotificacion("danger", res.mensaje);
                    btn.prop("disabled", false).html('<i class="fa-solid fa-check"></i> Aceptar');
                }
            }
        });
    });

    $(document).on("click", ".rechazar-btn", function() {
        const id = $(this).data("id");
        $("#motivo-" + id).slideToggle();
    });

    $(document).on("click", ".confirmar-rechazo", function() {
        const btn = $(this);
        const id = btn.data("id");
        const motivo = $("#motivo-" + id + " .motivo-input").val();
        btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span>');
        $.ajax({
            url: "ajax/aceptarRechazarPaseo.php",
            method: "POST",
            data: { idPaseo: id, accion: "rechazar", motivo: motivo },
            dataType: "json",
            success: function(res) {
                if (res.exito) {
                    $("#fila-" + id).fadeOut(300);
                    mostrarNotificacion("success", res.mensaje);
                } else {
                    mostrarNotificacion("danger", res.mensaje);
                    btn.prop("disabled", false).html("Confirmar");
                }
            }
        });
    });

    $(document).on("click", ".completar-btn", function() {
        const btn = $(this);
        const id = btn.data("id");
        if (!confirm("¿Completar este paseo?")) return;
        btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span>');
        $.ajax({
            url: "ajax/aceptarRechazarPaseo.php",
            method: "POST",
            data: { idPaseo: id, accion: "completar" },
            dataType: "json",
            success: function(res) {
                if (res.exito) {
                    $("#fila-aceptado-" + id).fadeOut(300);
                    mostrarNotificacion2("success", res.mensaje);
                } else {
                    mostrarNotificacion2("danger", res.mensaje);
                    btn.prop("disabled", false).html('<i class="fa-solid fa-flag-checkered"></i> Completar Paseo');
                }
            }
        });
    });

    $(document).on("click", ".cancelar-paseador-btn", function() {
        const btn = $(this);
        const id = btn.data("id");
        if (!confirm("¿Cancelar este paseo?")) return;
        btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span>');
        $.ajax({
            url: "ajax/aceptarRechazarPaseo.php",
            method: "POST",
            data: { idPaseo: id, accion: "cancelarp" },
            dataType: "json",
            success: function(res) {
                if (res.exito) {
                    $("#fila-aceptado-" + id).fadeOut(300);
                    mostrarNotificacion2("success", res.mensaje);
                } else {
                    mostrarNotificacion2("danger", res.mensaje);
                    btn.prop("disabled", false).html('<i class="fa-solid fa-ban"></i> Cancelar Paseo');
                }
            }
        });
    });

    function mostrarNotificacion2(tipo, mensaje) {
        const alerta = '<div class="alert alert-' + tipo + ' alert-dismissible fade show">' +
            mensaje + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        $("#notificacionAceptados").html(alerta);
        setTimeout(function() { $(".alert").alert("close"); }, 5000);
    }
});
</script>
