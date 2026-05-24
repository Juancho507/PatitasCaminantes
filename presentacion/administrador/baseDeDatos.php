<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
?>
<div class="container mt-4">
    <h3 class="mb-4"><i class="fas fa-database me-2"></i>Base de Datos</h3>

    <ul class="nav nav-pills mb-3" id="dbTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="db-views-tab" data-bs-toggle="pill" data-bs-target="#db-views" type="button" role="tab"><i class="fas fa-table me-1"></i>Vistas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="db-triggers-tab" data-bs-toggle="pill" data-bs-target="#db-triggers" type="button" role="tab"><i class="fas fa-bolt me-1"></i>Triggers</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="db-query-tab" data-bs-toggle="pill" data-bs-target="#db-query" type="button" role="tab"><i class="fas fa-terminal me-1"></i>Consultas SQL</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="db-logs-tab" data-bs-toggle="pill" data-bs-target="#db-logs" type="button" role="tab"><i class="fas fa-history me-1"></i>Logs</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="db-views" role="tabpanel">
            <div class="mb-3">
                <label for="selectView" class="form-label"><strong>Seleccionar Vista:</strong></label>
                <select id="selectView" class="form-select" onchange="cargarVista()">
                    <option value="">-- Seleccione una vista --</option>
                    <option value="vw_estadisticas_admin">vw_estadisticas_admin</option>
                    <option value="vw_aspirantes_pendientes">vw_aspirantes_pendientes</option>
                    <option value="vw_paseadores_activos">vw_paseadores_activos</option>
                    <option value="vw_perros_con_dueno">vw_perros_con_dueño</option>
                    <option value="vw_paseos_detalle">vw_paseos_detalle</option>
                    <option value="vw_paseos_pendientes_vencer">vw_paseos_pendientes_vencer</option>
                </select>
            </div>
            <div id="resultadoVista" class="table-responsive">
                <p class="text-muted">Seleccione una vista para ver su contenido.</p>
            </div>
        </div>

        <div class="tab-pane fade" id="db-triggers" role="tabpanel">
            <div id="resultadoTriggers">
                <p class="text-muted">Cargando triggers...</p>
            </div>
        </div>

        <div class="tab-pane fade" id="db-query" role="tabpanel">
            <div class="mb-3">
                <label for="sqlQuery" class="form-label"><strong>Escriba su consulta SQL (solo SELECT):</strong></label>
                <textarea id="sqlQuery" class="form-control font-monospace" rows="4" placeholder="SELECT * FROM ..."></textarea>
            </div>
            <button class="btn btn-primary" onclick="ejecutarConsulta()"><i class="fas fa-play me-1"></i>Ejecutar</button>
            <button class="btn btn-warning" onclick="document.getElementById('sqlQuery').value=''">Limpiar</button>
            <div id="resultadoQuery" class="mt-3 table-responsive"></div>
        </div>

        <div class="tab-pane fade" id="db-logs" role="tabpanel">
            <h5 class="mb-3"><i class="fas fa-history me-2"></i>Auditoría de cambios de estado</h5>
            <button class="btn btn-primary mb-3" onclick="cargarLogs()"><i class="fas fa-sync me-1"></i>Cargar Logs</button>
            <div id="resultadoLogs" class="table-responsive">
                <p class="text-muted">Presione "Cargar Logs" para ver los cambios de estado registrados.</p>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    window.cargarVista = function() {
        var vista = $("#selectView").val();
        if (!vista) {
            $("#resultadoVista").html('<p class="text-muted">Seleccione una vista para ver su contenido.</p>');
            return;
        }
        $.ajax({
            url: "ajax/dbAdminAjax.php",
            method: "POST",
            data: { accion: "ver_vista", vista: vista },
            dataType: "json",
            success: function(resp) {
                if (resp.exito) {
                    if (resp.datos && resp.datos.length > 0) {
                        var html = '<table class="table table-striped table-hover table-sm"><thead class="table-dark"><tr>';
                        $.each(resp.columnas, function(i, col) {
                            html += '<th>' + col + '</th>';
                        });
                        html += '</tr></thead><tbody>';
                        $.each(resp.datos, function(i, row) {
                            html += '<tr>';
                            $.each(resp.columnas, function(j, col) {
                                html += '<td>' + (row[col] != null ? row[col] : '<em class="text-muted">NULL</em>') + '</td>';
                            });
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        html += '<p class="text-muted">Total: ' + resp.datos.length + ' filas.</p>';
                        $("#resultadoVista").html(html);
                    } else {
                        $("#resultadoVista").html('<div class="alert alert-info">' + (resp.mensaje || 'Sin datos.') + '</div>');
                    }
                } else {
                    $("#resultadoVista").html('<div class="alert alert-danger">' + resp.mensaje + '</div>');
                }
            },
            error: function() {
                $("#resultadoVista").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    };

    window.ejecutarConsulta = function() {
        var sql = $("#sqlQuery").val().trim();
        if (!sql) {
            $("#resultadoQuery").html('<div class="alert alert-warning">Escriba una consulta SQL.</div>');
            return;
        }
        $("#resultadoQuery").html('<p class="text-muted">Ejecutando...</p>');
        $.ajax({
            url: "ajax/dbAdminAjax.php",
            method: "POST",
            data: { accion: "ejecutar_sql", sql: sql },
            dataType: "json",
            success: function(resp) {
                if (resp.exito) {
                    if (resp.datos && resp.datos.length > 0) {
                        var html = '<table class="table table-striped table-hover table-sm"><thead class="table-dark"><tr>';
                        $.each(resp.columnas, function(i, col) {
                            html += '<th>' + col + '</th>';
                        });
                        html += '</tr></thead><tbody>';
                        $.each(resp.datos, function(i, row) {
                            html += '<tr>';
                            $.each(resp.columnas, function(j, col) {
                                html += '<td>' + (row[col] != null ? row[col] : '<em class="text-muted">NULL</em>') + '</td>';
                            });
                            html += '</tr>';
                        });
                        html += '</tbody></table>';
                        html += '<p class="text-muted">Total: ' + resp.datos.length + ' filas.</p>';
                        $("#resultadoQuery").html(html);
                    } else {
                        $("#resultadoQuery").html('<div class="alert alert-info">' + (resp.mensaje || 'Consulta ejecutada.') + '</div>');
                    }
                } else {
                    $("#resultadoQuery").html('<div class="alert alert-danger">' + resp.mensaje + '</div>');
                }
            },
            error: function() {
                $("#resultadoQuery").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    };

    window.cargarLogs = function() {
        $.ajax({
            url: "ajax/dbAdminAjax.php",
            method: "POST",
            data: { accion: "ver_log" },
            dataType: "json",
            success: function(resp) {
                if (resp.exito && resp.datos && resp.datos.length > 0) {
                    var html = '<table class="table table-striped table-hover table-sm"><thead class="table-dark"><tr>';
                    $.each(resp.columnas, function(i, col) {
                        html += '<th>' + col + '</th>';
                    });
                    html += '</tr></thead><tbody>';
                    $.each(resp.datos, function(i, row) {
                        html += '<tr>';
                        $.each(resp.columnas, function(j, col) {
                            html += '<td>' + (row[col] != null ? row[col] : '<em class="text-muted">NULL</em>') + '</td>';
                        });
                        html += '</tr>';
                    });
                    html += '</tbody></table>';
                    html += '<p class="text-muted">Total: ' + resp.datos.length + ' registros.</p>';
                    $("#resultadoLogs").html(html);
                } else {
                    $("#resultadoLogs").html('<div class="alert alert-info">' + (resp.mensaje || 'No hay registros en el log.') + '</div>');
                }
            },
            error: function() {
                $("#resultadoLogs").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    };

    // Load triggers
    $("#db-triggers-tab").on("shown.bs.tab", function() {
        $.ajax({
            url: "ajax/dbAdminAjax.php",
            method: "POST",
            data: { accion: "ver_triggers" },
            dataType: "json",
            success: function(resp) {
                if (resp.exito && resp.datos.length > 0) {
                    var descripciones = {
                        "trg_evitar_choque_horario": "Evita que un perro tenga dos paseos a la misma hora.",
                        "trg_validar_perro_peligroso": "Rechaza el paseo si el perro es Peligroso y el paseador no está aprobado.",
                        "trg_validar_capacidad_riesgo": "Rechaza si se excede la capacidad según el riesgo del perro (Bajo=5, Medio=3, Alto=2, Peligroso=1).",
                        "trg_bloquear_horario_peligroso": "Bloquea el horario del paseador si ya tiene un paseo con perro peligroso.",
                        "trg_auditar_estado_paseo": "Guarda en log_paseo cada cambio de estado del paseo."
                    };
                    var html = '<table class="table table-striped table-hover table-sm"><thead class="table-dark"><tr><th>Trigger</th><th>Event</th><th>Table</th><th>Statement</th><th>Qué hace</th></tr></thead><tbody>';
                    $.each(resp.datos, function(i, t) {
                        var nombre = t.Trigger || t.TRIGGER_NAME || '';
                        var evento = t.Event || t.EVENT_MANIPULATION || '';
                        var tabla = t.Table || t.EVENT_OBJECT_TABLE || '';
                        var statement = t.Statement || t.ACTION_STATEMENT || '';
                        var desc = descripciones[nombre] || '';
                        html += '<tr>';
                        html += '<td><strong>' + nombre + '</strong></td>';
                        html += '<td>' + evento + '</td>';
                        html += '<td>' + tabla + '</td>';
                        html += '<td><pre class="mb-0" style="max-height:100px;overflow:auto;font-size:11px;">' + $("<div>").text(statement).html() + '</pre></td>';
                        html += '<td><em>' + desc + '</em></td>';
                        html += '</tr>';
                    });
                    html += '</tbody></table>';
                    $("#resultadoTriggers").html(html);
                } else {
                    $("#resultadoTriggers").html('<div class="alert alert-info">No hay triggers definidos.</div>');
                }
            },
            error: function() {
                $("#resultadoTriggers").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });
});
</script>
