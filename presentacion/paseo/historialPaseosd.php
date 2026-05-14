<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
include("presentacion/encabezadoD.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
?>

<div class="container mt-4">
    <h4>Historial de Paseos (Dueño)</h4>
    <input type="text" id="filtroD" class="form-control" placeholder="Buscar por perro, paseador, estado, fecha, hora o precio...">
    <div id="resultadoHistorialD" class="mt-3"></div>
</div>

<script>
function resaltar(texto, palabras) {
    palabras.forEach(p => {
        const reg = new RegExp("(" + p.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")", "gi");
        texto = texto.replace(reg, "<strong>$1</strong>");
    });
    return texto;
}

function buscarHistorialD() {
    const q = document.getElementById("filtroD").value;
    const palabras = q.trim().split(/\s+/);

    fetch("ajax/buscarHistorialD.php?q=" + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
            let html = "";
            if (data.length > 0) {
                html += `<table class='table table-striped'><thead><tr>
                            <th>Fecha</th><th>Inicio</th><th>Fin</th>
                            <th>Perro</th><th>Paseador</th><th>Precio</th><th>Estado</th>
                        </tr></thead><tbody>`;
                data.forEach(item => {
                    html += "<tr>";
                    html += `<td>${resaltar(item.fecha, palabras)}</td>`;
                    html += `<td>${resaltar(item.inicio, palabras)}</td>`;
                    html += `<td>${resaltar(item.fin, palabras)}</td>`;
                    html += `<td>${resaltar(item.perro, palabras)}</td>`;
                    html += `<td>${resaltar(item.paseador, palabras)}</td>`;
                    html += `<td>$${resaltar(item.precio, palabras)}</td>`;
                    html += `<td>${resaltar(item.estado, palabras)}</td>`;
                    html += "</tr>";
                });
                html += "</tbody></table>";
            } else {
                html = "<div class='alert alert-warning'>No se encontraron resultados.</div>";
            }
            document.getElementById("resultadoHistorialD").innerHTML = html;
        });
}

document.getElementById("filtroD").addEventListener("input", buscarHistorialD);
window.addEventListener("load", buscarHistorialD);
</script>
