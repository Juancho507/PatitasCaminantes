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
                            <th>Perro</th><th>Paseador</th><th>Precio</th><th>Estado</th><th>Acción</th>
                        </tr></thead><tbody>`;
                data.forEach(item => {
                    let estadoBadge = "";
                    if (item.idEstado === 5) {
                        estadoBadge = `<span class="badge bg-danger">${item.estado}</span>`;
                    } else if (item.idEstado === 6) {
                        estadoBadge = `<span class="badge bg-warning text-dark">${item.estado}</span>`;
                    } else if (item.idEstado === 4) {
                        estadoBadge = `<span class="badge bg-success">${item.estado}</span>`;
                    } else if (item.idEstado === 2) {
                        estadoBadge = `<span class="badge bg-primary">${item.estado}</span>`;
                    } else if (item.idEstado === 1) {
                        estadoBadge = `<span class="badge bg-secondary">${item.estado}</span>`;
                    } else {
                        estadoBadge = `<span class="badge bg-info">${item.estado}</span>`;
                    }

                    let accion = "";
                    if (item.idEstado === 1 || item.idEstado === 2) {
                        accion = `<button class="btn btn-danger btn-sm btn-cancelar-paseo" data-id="${item.id}">Cancelar</button>`;
                    }

                    html += "<tr>";
                    html += `<td>${resaltar(item.fecha, palabras)}</td>`;
                    html += `<td>${resaltar(item.inicio, palabras)}</td>`;
                    html += `<td>${resaltar(item.fin, palabras)}</td>`;
                    html += `<td>${resaltar(item.perro, palabras)}</td>`;
                    html += `<td>${resaltar(item.paseador, palabras)}</td>`;
                    html += `<td>$${resaltar(item.precio, palabras)}</td>`;
                    html += `<td>${estadoBadge}</td>`;
                    html += `<td>${accion}</td>`;
                    html += "</tr>";
                });
                html += "</tbody></table>";
            } else {
                html = "<div class='alert alert-warning'>No se encontraron resultados.</div>";
            }
            document.getElementById("resultadoHistorialD").innerHTML = html;
        });
}

document.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-cancelar-paseo")) {
        const idPaseo = e.target.dataset.id;
        if (!confirm("¿Estás seguro de cancelar este paseo?\n\nSi cancelas con menos de 2 horas de anticipación, tu cuenta será bloqueada.")) return;

        const btn = e.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        fetch("ajax/cancelarPaseo.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "idPaseo=" + idPaseo
        })
        .then(res => res.json())
        .then(resp => {
            alert(resp.mensaje);
            if (resp.exito) {
                buscarHistorialD();
            } else {
                btn.disabled = false;
                btn.textContent = "Cancelar";
            }
        })
        .catch(() => {
            alert("Error de conexión.");
            btn.disabled = false;
            btn.textContent = "Cancelar";
        });
    }
});

document.getElementById("filtroD").addEventListener("input", buscarHistorialD);
window.addEventListener("load", buscarHistorialD);
</script>
