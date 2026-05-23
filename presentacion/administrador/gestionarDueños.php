<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];

include("presentacion/encabezadoA.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$conexion = new Conexion();
$conexion->abrir();
$conexion->ejecutar("SELECT idDueño, NroDocumento, Nombre, Apellido, Correo, Contacto, Estado_idEstado, Direccion FROM Dueño ORDER BY idDueño DESC");
$dueños = [];
while ($reg = $conexion->registro()) {
    $dueños[] = $reg;
}
$conexion->cerrar();
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-users me-2"></i>Gestionar Dueños</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="filtroDueno" class="form-control" placeholder="Buscar por nombre, correo o documento...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover" id="tablaDuenos">
            <thead class="table-dark">
                <tr>
                    <th>Nro Documento</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Contacto</th>
                    <th>Activo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dueños as $d): ?>
                <tr>
                    <td class="td-doc"><?php echo htmlspecialchars($d[1]); ?></td>
                    <td class="td-nombre"><?php echo htmlspecialchars($d[2]); ?></td>
                    <td class="td-apellido"><?php echo htmlspecialchars($d[3]); ?></td>
                    <td class="td-correo"><?php echo htmlspecialchars($d[4]); ?></td>
                    <td class="td-contacto"><?php echo htmlspecialchars($d[5]); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $d[6] == 2 ? 'success' : 'danger'; ?> estado-badge" data-id="<?php echo $d[0]; ?>">
                            <?php echo $d[6] == 2 ? 'Sí' : 'No'; ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-<?php echo $d[6] == 2 ? 'warning' : 'success'; ?> toggle-dueno"
                            data-id="<?php echo $d[0]; ?>"
                            data-activo="<?php echo $d[6]; ?>">
                            <i class="fas fa-<?php echo $d[6] == 2 ? 'ban' : 'check'; ?>"></i>
                            <?php echo $d[6] == 2 ? 'Deshabilitar' : 'Habilitar'; ?>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="respuestaAjax" class="mt-3"></div>
</div>

<script>
function resaltar(texto, palabras) {
    palabras.forEach(function(p) {
        if (p.trim() === "") return;
        var re = new RegExp("(" + p.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ")", "gi");
        texto = texto.replace(re, "<strong>$1</strong>");
    });
    return texto;
}

function aplicarFiltro() {
    var filtro = $("#filtroDueno").val().toLowerCase().trim();
    var palabras = filtro.split(/\s+/);

    $("#tablaDuenos tbody tr").each(function() {
        var fila = $(this);
        var doc = fila.find(".td-doc").text();
        var nombre = fila.find(".td-nombre").text();
        var apellido = fila.find(".td-apellido").text();
        var correo = fila.find(".td-correo").text();
        var contacto = fila.find(".td-contacto").text();
        var textoCompleto = (doc + " " + nombre + " " + apellido + " " + correo + " " + contacto).toLowerCase();
        var coincide = palabras.every(function(p) { return textoCompleto.includes(p); });

        if (coincide) {
            fila.show();
            fila.find(".td-nombre").html(resaltar(nombre, palabras));
            fila.find(".td-apellido").html(resaltar(apellido, palabras));
            fila.find(".td-correo").html(resaltar(correo, palabras));
            fila.find(".td-doc").html(resaltar(doc, palabras));
            fila.find(".td-contacto").html(resaltar(contacto, palabras));
        } else {
            fila.hide();
        }
    });
}

$(document).ready(function() {
    $("#filtroDueno").on("input", aplicarFiltro);

    $(document).on("click", ".toggle-dueno", function() {
        var btn = $(this);
        var id = btn.data("id");
        var activoActual = btn.data("activo");
        var nuevoActivo = activoActual == 2 ? 4 : 2;

        $.ajax({
            url: "ajax/gestionarDueñoEstado.php",
            method: "POST",
            data: { id: id, activo: nuevoActivo },
            success: function(response) {
                if (response.trim() === "ok") {
                    var fila = btn.closest("tr");
                    var badge = fila.find(".estado-badge");
                    badge.text(nuevoActivo == 2 ? "Sí" : "No")
                        .removeClass("bg-success bg-danger")
                        .addClass(nuevoActivo == 2 ? "bg-success" : "bg-danger");

                    btn.html(nuevoActivo == 2 ? '<i class="fas fa-ban"></i> Deshabilitar' : '<i class="fas fa-check"></i> Habilitar')
                        .removeClass("btn-warning btn-success")
                        .addClass(nuevoActivo == 2 ? "btn-warning" : "btn-success");
                    btn.data("activo", nuevoActivo);

                    $("#respuestaAjax").html('<div class="alert alert-success alert-dismissible fade show" role="alert">Estado actualizado correctamente.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                } else {
                    $("#respuestaAjax").html('<div class="alert alert-danger">Error al actualizar.</div>');
                }
            },
            error: function() {
                $("#respuestaAjax").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });

});
</script>
