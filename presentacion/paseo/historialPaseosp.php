<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
include("presentacion/encabezadoP.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
?>

<div class="container mt-4">
    <h4>Historial de Paseos (Paseador)</h4>
    <input type="text" id="filtroP" class="form-control" placeholder="Buscar por perro, dueño, estado, fecha, hora o precio...">
    <div id="resultadoHistorialP" class="mt-3"></div>
</div>

<script>
function resaltar(texto, palabras) {
    palabras.forEach(p => {
        const reg = new RegExp("(" + p.replace(/[.*+?^${}()|[\]\\]/g, "\\$&") + ")", "gi");
        texto = texto.replace(reg, "<strong>$1</strong>");
    });
    return texto;
}

function buscarHistorialP() {
    const q = document.getElementById("filtroP").value;
    const palabras = q.trim().split(/\s+/);

    fetch("ajax/buscarHistorialP.php?q=" + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
            let html = "";
            if (data.length > 0) {
                html += `<table class='table table-striped'><thead><tr>
                            <th>Fecha</th><th>Inicio</th><th>Fin</th>
                            <th>Perro</th><th>Dueño</th><th>Precio</th><th>Estado</th>
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
            document.getElementById("resultadoHistorialP").innerHTML = html;
        });
}

document.getElementById("filtroP").addEventListener("input", buscarHistorialP);
window.addEventListener("load", buscarHistorialP);
</script>
