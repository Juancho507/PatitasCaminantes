<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$peligrosidadLogica = new Peligrosidad();
$peligrosidades = $peligrosidadLogica->consultarTodos();
$mapaPeligrosidad = [];
foreach ($peligrosidades as $p) {
    $mapaPeligrosidad[$p->getId()] = $p->getNivel();
}
?>
<body>
<?php
include("presentacion/encabezadoD.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
$perro = new Perro();
$perros = $perro->consultar($rol, $id);
?>
<div class="container">
    <div class="row mt-4">
        <div class="col">
            <div class="card">
                <div class="card-header"><h4>Listado de Perros</h4></div>
                <div class="card-body">
                    <?php
                    $cantidadPerros = count($perros);
                    echo "<strong>" . $cantidadPerros . " perro" . ($cantidadPerros !== 1 ? "s" : "") . " encontrado" . ($cantidadPerros !== 1 ? "s" : "") . "</strong><br>";

                    if (empty($perros)) {
                        echo "<div class='alert alert-warning mt-3'>No se encontraron perros para mostrar.</div>";
                    } else {
                        echo "<table class='table table-striped table-hover mt-3'>";
                        echo "<thead><tr><th>Foto</th><th>Nombre</th><th>Raza</th><th>Tamaño</th><th>Peso</th><th>Peligrosidad</th><th>Activo</th><th>Recomendacion</th><th>Acción</th></tr></thead>";
                        echo "<tbody>";
                        foreach($perros as $perroItem){
                            $peligrosidadId = $perroItem->getPeligrosidad();
                            $peligrosidadNivel = isset($mapaPeligrosidad[$peligrosidadId]) ? $mapaPeligrosidad[$peligrosidadId] : $peligrosidadId;
                            $activo = $perroItem->getActivo();
                            $esActivo = ($activo == 2);
                            echo "<tr id='perro-fila-" . $perroItem->getId() . "'>";

                            echo "<td class='align-middle py-3'>";
                            if ($perroItem->getFoto() != "") {
                                echo "<img src='" . $perroItem->getFoto() . "' width='80' height='80' class='rounded-circle' style='object-fit:cover;' />";
                            } else {
                                echo "<div class='text-muted'>Sin foto</div>";
                            }
                            echo "</td>";

                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getNombre()) . "</td>";
                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getRaza()) . "</td>";
                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getTamaño()) . "</td>";
                            echo "<td class='align-middle py-3'>" . htmlspecialchars($perroItem->getPeso()) . " kg</td>";
                            echo "<td class='align-middle py-3'><span class='badge bg-" . ($peligrosidadNivel === "PELIGROSO" ? "danger" : ($peligrosidadNivel === "ALTO" ? "warning" : "success")) . "'>" . htmlspecialchars($peligrosidadNivel) . "</span></td>";
                            echo "<td class='align-middle py-3'><span class='badge bg-" . ($esActivo ? "success" : "secondary") . " estado-perro' data-id='" . $perroItem->getId() . "'>" . ($esActivo ? "Activo" : "Inactivo") . "</span></td>";
                            echo "<td class='align-middle py-3'>" . nl2br(htmlspecialchars($perroItem->getRecomendacion())) . "</td>";

                            echo "<td class='align-middle py-3'>";
                            echo "<a href='?pid=" . base64_encode("presentacion/perro/editarPerro.php") . "&id=" . htmlspecialchars($perroItem->getId()) . "' class='btn btn-warning btn-sm' style='margin-right: 5px;'>Editar</a>";
                            echo "<button class='btn btn-sm " . ($esActivo ? "btn-secondary" : "btn-success") . " btn-toggle-perro' data-id='" . htmlspecialchars($perroItem->getId()) . "' data-activo='" . $activo . "'>" . ($esActivo ? "Inactivar" : "Activar") . "</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="respuestaAjax" class="container mt-2"></div>

<script>
$(document).ready(function(){
    $(document).on("click", ".btn-toggle-perro", function(){
        var btn = $(this);
        var idPerro = btn.data("id");
        var activoActual = btn.data("activo");
        var nuevoActivo = activoActual == 2 ? 4 : 2;
        var texto = nuevoActivo == 2 ? "activar" : "inactivar";

        if (!confirm("¿Estás seguro de " + texto + " este perro?")) return;

        $.ajax({
            url: "ajax/togglePerroEstado.php",
            method: "POST",
            data: { id: idPerro, activo: nuevoActivo },
            success: function(response){
                if (response.trim() === "ok") {
                    var fila = $("#perro-fila-" + idPerro);
                    var badge = fila.find(".estado-perro");
                    badge.text(nuevoActivo == 2 ? "Activo" : "Inactivo")
                        .removeClass("bg-success bg-secondary")
                        .addClass(nuevoActivo == 2 ? "bg-success" : "bg-secondary");

                    btn.text(nuevoActivo == 2 ? "Inactivar" : "Activar")
                        .removeClass("btn-success btn-secondary")
                        .addClass(nuevoActivo == 2 ? "btn-secondary" : "btn-success");
                    btn.data("activo", nuevoActivo);

                    $("#respuestaAjax").html('<div class="alert alert-success alert-dismissible fade show">Perro ' + texto + ' correctamente.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                } else {
                    $("#respuestaAjax").html('<div class="alert alert-danger">Error al cambiar estado.</div>');
                }
            },
            error: function(){
                $("#respuestaAjax").html('<div class="alert alert-danger">Error de comunicación.</div>');
            }
        });
    });
});
</script>

</body>
</html>
