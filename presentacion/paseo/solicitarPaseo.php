<?php
$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
$mensaje = "";

$perro = new Perro();
$listaPerros = $perro->consultar("dueño", $id);

$dueñoLocalidad = new Dueño($id);
$dueñoLocalidad->consultar();
$localidadId = $dueñoLocalidad->getLocalidadId();

$paseador = new Paseador();
$paseadores = $paseador->consultarActivos($localidadId);

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
?>

<style>
.calendar-day {
    text-align: center;
    vertical-align: middle;
    height: 48px;
    border: 1px solid #dee2e6;
    cursor: pointer;
    font-size: 0.9rem;
    border-radius: 4px;
    transition: all 0.2s;
}
.calendar-day:hover:not(.disabled):not(.empty) {
    background-color: #e9ecef;
}
.calendar-day.available {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    font-weight: bold;
}
.calendar-day.available:hover {
    background-color: #b8dfc6;
}
.calendar-day.selected {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
}
.calendar-day.disabled {
    background-color: #f8f9fa;
    color: #adb5bd;
    cursor: not-allowed;
    opacity: 0.6;
}
.calendar-day.empty {
    border: none;
    cursor: default;
}
.calendar-day.today {
    outline: 2px solid #007bff;
    outline-offset: -2px;
}
.mes-navegacion {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.hora-slot {
    display: inline-block;
    padding: 8px 16px;
    margin: 4px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}
.hora-slot:hover {
    background-color: #e9ecef;
}
.hora-slot.selected {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}
.step-indicator {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 10px;
}
.step-circle.active {
    background-color: #007bff;
    color: #fff;
}
.step-circle.completed {
    background-color: #28a745;
    color: #fff;
}
.step-connector {
    width: 60px;
    height: 2px;
    background-color: #e9ecef;
    align-self: center;
}
.step-connector.completed {
    background-color: #28a745;
}
.perro-card {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.2s;
    margin-bottom: 10px;
}
.perro-card:hover {
    border-color: #007bff;
    background-color: #f0f8ff;
}
.perro-card.selected {
    border-color: #007bff;
    background-color: #e3f2fd;
}
.paseador-card {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.2s;
    height: 100%;
}
.paseador-card:hover {
    border-color: #007bff;
    background-color: #f0f8ff;
}
.paseador-card.selected {
    border-color: #007bff;
    background-color: #e3f2fd;
}
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h4>Solicitar Paseo</h4>
                </div>
                <div class="card-body">

                    <div class="step-indicator">
                        <div class="step-circle active" id="stepCircle1">1</div>
                        <div class="step-connector" id="stepConnector1"></div>
                        <div class="step-circle" id="stepCircle2">2</div>
                        <div class="step-connector" id="stepConnector2"></div>
                        <div class="step-circle" id="stepCircle3">3</div>
                    </div>
                    <p class="text-center text-muted" id="stepLabel">Paso 1: Selecciona tus perros</p>

                    <div id="mensajeContainer"></div>

                    <form id="paseoForm">
                        <div id="step1">
                            <h5>Selecciona hasta 2 perros</h5>
                            <p class="text-muted">Selecciona los perros que deseas pasear. Puedes seleccionar 1 o 2 perros.</p>
                            <div class="row">
                                <?php foreach ($listaPerros as $p):
                                    $peligrosidadId = $p->getPeligrosidad();
                                    $peligrosidadNivel = isset($mapaPeligrosidad[$peligrosidadId]) ? $mapaPeligrosidad[$peligrosidadId] : "Desconocido";
                                ?>
                                <div class="col-md-4">
                                    <div class="perro-card" data-id="<?php echo $p->getId(); ?>" data-peligrosidad="<?php echo $peligrosidadId; ?>" data-peligrosidad-nombre="<?php echo htmlspecialchars($peligrosidadNivel); ?>">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <?php if ($p->getFoto() != "" && file_exists($p->getFoto())): ?>
                                                    <img src="<?php echo $p->getFoto(); ?>" width="60" height="60" class="rounded-circle" style="object-fit:cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:60px;height:60px;">
                                                        <i class="fas fa-dog"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <strong><?php echo htmlspecialchars($p->getNombre()); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($p->getRaza()); ?> - <?php echo $p->getPeso(); ?> kg</small><br>
                                                <span class="badge bg-<?php echo ($peligrosidadNivel === "PELIGROSO" ? "danger" : ($peligrosidadNivel === "ALTO" ? "warning" : ($peligrosidadNivel === "MEDIO" ? "info" : "success"))); ?>">
                                                    <?php echo htmlspecialchars($peligrosidadNivel); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="checkbox" name="perros[]" value="<?php echo $p->getId(); ?>" class="d-none perro-checkbox">
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div id="capacidadInfo" class="mt-2"></div>
                            <div id="bozalWarning" class="alert alert-warning mt-2 d-none">
                                <i class="fas fa-exclamation-triangle"></i> Bozal obligatorio según ley colombiana para perros de nivel PELIGROSO.
                            </div>
                            <button type="button" class="btn btn-primary mt-3" id="btnPaso2" disabled>Siguiente: Seleccionar Paseador</button>
                        </div>

                        <div id="step2" style="display:none;">
                            <h5>Selecciona un paseador</h5>
                            <p class="text-muted">Elige el paseador que realizará el paseo.</p>
                            <div id="peligrosoWarningStep2" class="alert alert-warning d-none">
                                <i class="fas fa-exclamation-triangle"></i> Has seleccionado un perro <strong>PELIGROSO</strong>. Solo se muestran paseadores aprobados para manejar perros peligrosos.
                            </div>
                            <div class="row" id="paseadoresContainer">
                                <?php foreach ($paseadores as $p): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="paseador-card" data-id="<?php echo $p->getId(); ?>" data-aprobado-peligroso="<?php echo $p->getAprobadoPeligroso(); ?>">
                                        <div class="text-center">
                                            <?php if ($p->getFoto() != "" && file_exists($p->getFoto())): ?>
                                                <img src="<?php echo $p->getFoto(); ?>" class="rounded-circle mb-2" width="80" height="80" style="object-fit:cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-2" style="width:80px;height:80px;">
                                                    <i class="fas fa-user fa-2x"></i>
                                                </div>
                                            <?php endif; ?>
                                            <h6><?php echo htmlspecialchars($p->getNombre() . " " . $p->getApellido()); ?></h6>
                                            <p class="small text-muted"><?php echo htmlspecialchars($p->getInformacion()); ?></p>
                                            <?php
                                            $tarifas = $p->getTarifas();
                                            if (!empty($tarifas)) {
                                                echo "<p class='small mb-1'><strong>Tarifas:</strong></p>";
                                                foreach ($tarifas as $t) {
                                                    echo "<span class='badge bg-info me-1'>" . htmlspecialchars($t->getNombrePeligrosidad()) . ": $" . number_format($t->getPrecioHora(), 0, ',', '.') . "/h</span>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <input type="radio" name="idPaseador" value="<?php echo $p->getId(); ?>" class="d-none paseador-radio">
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-secondary mt-3" id="btnVolverPaso1">Anterior</button>
                            <button type="button" class="btn btn-primary mt-3" id="btnPaso3" disabled>Siguiente: Elegir Fecha y Hora</button>
                        </div>

                        <div id="step3" style="display:none;">
                            <h5>Elige fecha y hora del paseo</h5>
                            <div class="alert alert-info">
                                <i class="fas fa-clock"></i> Los paseos tienen una duración de <strong>1 hora</strong>.
                            </div>
                            <p class="text-muted" id="paseadorSeleccionadoInfo"></p>

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="mes-navegacion">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnMesAnterior">&laquo; Anterior</button>
                                        <strong><span id="mesActualLabel"></span></strong>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnMesSiguiente">Siguiente &raquo;</button>
                                    </div>
                                    <table class="table table-bordered text-center" id="calendario">
                                        <thead>
                                            <tr>
                                                <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
                                            </tr>
                                        </thead>
                                        <tbody id="calendarioBody"></tbody>
                                    </table>
                                </div>
                                <div class="col-md-5">
                                    <h6>Horarios disponibles</h6>
                                    <div id="horasDisponibles" class="p-3 border rounded bg-light">
                                        <p class="text-muted">Selecciona un día disponible en el calendario.</p>
                                    </div>
                                    <div id="bozalCheckContainer" class="mt-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="bozalCheck">
                                            <label class="form-check-label" for="bozalCheck">
                                                <i class="fas fa-shield-dog"></i> Bozal obligatorio según ley colombiana
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary mt-3" id="btnVolverPaso2">Anterior</button>
                            <button type="button" class="btn btn-success mt-3" id="btnSolicitar" disabled>Solicitar Paseo</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
var pasoActual = 1;
var perrosSeleccionados = [];
var paseadorSeleccionado = null;
var mesActual = <?php echo (int)date('n'); ?>;
var añoActual = <?php echo (int)date('Y'); ?>;
var mesMaximo = mesActual + 1;
var añoMaximo = añoActual;
if (mesMaximo > 12) {
    mesMaximo = 1;
    añoMaximo++;
}
var fechaSeleccionada = null;
var horaSeleccionada = null;
var necesitaBozal = false;

function actualizarPaso(paso) {
    pasoActual = paso;
    $("#step1, #step2, #step3").hide();
    $("#step" + paso).show();
    $(".step-circle").removeClass("active completed");
    $(".step-connector").removeClass("completed");
    for (var i = 1; i <= 3; i++) {
        if (i < paso) {
            $("#stepCircle" + i).addClass("completed").text("\u2713");
            if (i < 3) $("#stepConnector" + i).addClass("completed");
        } else if (i === paso) {
            $("#stepCircle" + i).addClass("active").text(i);
        }
    }
    var labels = {1: "Paso 1: Selecciona tus perros", 2: "Paso 2: Selecciona un paseador", 3: "Paso 3: Elige fecha y hora"};
    $("#stepLabel").text(labels[paso]);
}

function filtrarPaseadoresPeligroso() {
    var hayPeligroso = false;
    $(".perro-card.selected").each(function(){
        if ($(this).data("peligrosidad-nombre") === "PELIGROSO") hayPeligroso = true;
    });

    if (hayPeligroso) {
        $("#peligrosoWarningStep2").removeClass("d-none");
        $(".paseador-card").each(function(){
            if ($(this).data("aprobado-peligroso") != 1) {
                $(this).hide();
                if ($(this).hasClass("selected")) {
                    $(this).removeClass("selected");
                    $(this).find(".paseador-radio").prop("checked", false);
                    paseadorSeleccionado = null;
                    $("#btnPaso3").prop("disabled", true);
                }
            }
        });
    } else {
        $("#peligrosoWarningStep2").addClass("d-none");
        $(".paseador-card").show();
    }
}

$(".perro-card").click(function(){
    var checkbox = $(this).find(".perro-checkbox");
    var id = $(this).data("id");
    var wasSelected = $(this).hasClass("selected");

    if (wasSelected) {
        $(this).removeClass("selected");
        checkbox.prop("checked", false);
        perrosSeleccionados = perrosSeleccionados.filter(function(v){ return v != id; });
    } else {
        if (perrosSeleccionados.length >= 2) {
            alert("Solo puedes seleccionar hasta 2 perros.");
            return;
        }
        $(this).addClass("selected");
        checkbox.prop("checked", true);
        perrosSeleccionados.push(id);
    }

    actualizarCapacidad();
    filtrarPaseadoresPeligroso();
    $("#btnPaso2").prop("disabled", perrosSeleccionados.length === 0);
});

function actualizarCapacidad() {
    if (perrosSeleccionados.length === 0) {
        $("#capacidadInfo").html("");
        $("#bozalWarning").addClass("d-none");
        necesitaBozal = false;
        return;
    }

    var idsStr = perrosSeleccionados.join(",");
    $.ajax({
        url: "ajax/consultarCapacidad.php",
        method: "GET",
        data: { idPerros: idsStr },
        dataType: "json",
        success: function(respuesta){
            var tipoAlerta = respuesta.valido ? "info" : "danger";
            var html = "<div class='alert alert-" + tipoAlerta + "'>";
            html += "<strong>Capacidad:</strong> " + respuesta.mensaje;
            html += "</div>";
            $("#capacidadInfo").html(html);

            necesitaBozal = (respuesta.nivelMaximo === "Peligroso");
            if (necesitaBozal) {
                $("#bozalWarning").removeClass("d-none");
            } else {
                $("#bozalWarning").addClass("d-none");
            }

            $("#btnPaso2").prop("disabled", perrosSeleccionados.length === 0 || !respuesta.valido);
        }
    });
}

$(".paseador-card").click(function(){
    $(".paseador-card").removeClass("selected");
    $(this).addClass("selected");
    $(this).find(".paseador-radio").prop("checked", true);
    paseadorSeleccionado = $(this).data("id");
    $("#btnPaso3").prop("disabled", false);
});

$("#btnPaso2").click(function(){
    actualizarPaso(2);
});

$("#btnVolverPaso1").click(function(){
    actualizarPaso(1);
});

$("#btnPaso3").click(function(){
    actualizarPaso(3);
    var nombrePaseador = $(".paseador-card.selected h6").text();
    $("#paseadorSeleccionadoInfo").text("Paseador seleccionado: " + nombrePaseador);
    cargarCalendario(mesActual, añoActual);
});

$("#btnVolverPaso2").click(function(){
    actualizarPaso(2);
});

function cargarCalendario(mes, año) {
    $("#calendarioBody").html('<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"></div> Cargando...</td></tr>');

    $.ajax({
        url: "ajax/cargarCalendario.php",
        method: "GET",
        data: { idPaseador: paseadorSeleccionado, mes: mes, año: año },
        dataType: "json",
        success: function(respuesta){
            var dias = respuesta.diasDisponibles;
            var primerDia = new Date(año, mes - 1, 1).getDay();
            primerDia = (primerDia === 0) ? 6 : primerDia - 1;
            var ultimoDia = new Date(año, mes, 0).getDate();

            var nombreMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            $("#mesActualLabel").text(nombreMeses[mes - 1] + " " + año);

            var html = "";
            var diaActual = new Date();
            diaActual.setHours(0,0,0,0);

            html += "<tr>";
            for (var i = 0; i < primerDia; i++) {
                html += '<td class="calendar-day empty"></td>';
            }

            var maxDia = ultimoDia;
            if (mes === mesActual && año === añoActual) {
                maxDia = Math.min(ultimoDia, diaActual.getDate());
            } else if (mes > mesActual || año > añoActual) {
                maxDia = ultimoDia;
            }

            for (var dia = 1; dia <= ultimoDia; dia++) {
                if ((primerDia + dia - 1) % 7 === 0 && dia > 1) {
                    html += "</tr><tr>";
                }

                var fechaObj = new Date(año, mes - 1, dia);
                var fechaStr = año + "-" + (mes < 10 ? "0" : "") + mes + "-" + (dia < 10 ? "0" : "") + dia;
                var diaSemana = fechaObj.getDay();
                var esFinde = (diaSemana === 0 || diaSemana === 6);
                var esHoy = (fechaObj.getTime() === diaActual.getTime());
                var esPasado = fechaObj < diaActual;
                var disponible = dias[dia] && dias[dia].disponible === true;
                var enMesSiguiente = false;

                var diasDisponiblesArray = Object.keys(dias).filter(function(k){ return dias[k].disponible === true; });
                var esDisponible = dias[dia] && dias[dia].disponible === true;

                var clases = "calendar-day";
                if (esPasado) {
                    clases += " disabled";
                } else if (esDisponible) {
                    clases += " available";
                } else {
                    clases += " disabled";
                }
                if (esHoy) clases += " today";
                if (fechaSeleccionada === fechaStr) clases += " selected";

                html += '<td class="' + clases + '" data-fecha="' + fechaStr + '" data-dia="' + dia + '" data-mes="' + mes + '" data-año="' + año + '"';
                if (!esPasado && esDisponible) {
                    html += ' style="cursor:pointer;"';
                }
                html += ">" + dia + "</td>";
            }

            var totalCeldas = primerDia + ultimoDia;
            var resto = 7 - (totalCeldas % 7);
            if (resto < 7) {
                for (var i = 0; i < resto; i++) {
                    html += '<td class="calendar-day empty"></td>';
                }
            }

            html += "</tr>";
            $("#calendarioBody").html(html);

            $(".calendar-day.available").click(function(){
                $(".calendar-day").removeClass("selected");
                $(this).addClass("selected");
                fechaSeleccionada = $(this).data("fecha");
                var dia = $(this).data("dia");
                var horas = dias[dia] ? dias[dia].horas : [];
                mostrarHoras(horas);
            });
        },
        error: function(){
            $("#calendarioBody").html('<tr><td colspan="7" class="text-center text-danger">Error al cargar el calendario.</td></tr>');
        }
    });
}

function mostrarHoras(horas) {
    if (!horas || horas.length === 0) {
        $("#horasDisponibles").html('<p class="text-muted">No hay horarios disponibles para este día.</p>');
        $("#btnSolicitar").prop("disabled", true);
        return;
    }

    var html = '<p class="mb-2"><strong>Horarios disponibles:</strong></p>';
    horas.forEach(function(h){
        html += '<span class="hora-slot" data-hora="' + h + '">' + h + '</span>';
    });
    $("#horasDisponibles").html(html);

    $(".hora-slot").click(function(){
        $(".hora-slot").removeClass("selected");
        $(this).addClass("selected");
        horaSeleccionada = $(this).data("hora");

        if (necesitaBozal) {
            if (!$("#bozalCheck").prop("checked")) {
                $("#btnSolicitar").prop("disabled", true);
            } else {
                $("#btnSolicitar").prop("disabled", false);
            }
        } else {
            $("#btnSolicitar").prop("disabled", false);
        }
    });
}

$("#bozalCheck").change(function(){
    if (necesitaBozal) {
        $("#btnSolicitar").prop("disabled", !$(this).prop("checked"));
    }
});

$("#btnMesAnterior").click(function(){
    if (mesActual > <?php echo (int)date('n'); ?> || añoActual > <?php echo (int)date('Y'); ?>) {
        mesActual--;
        if (mesActual < 1) { mesActual = 12; añoActual--; }
        cargarCalendario(mesActual, añoActual);
    }
});

$("#btnMesSiguiente").click(function(){
    var nuevoMes = mesActual + 1;
    var nuevoAño = añoActual;
    if (nuevoMes > 12) { nuevoMes = 1; nuevoAño++; }
    if (nuevoAño > añoMaximo || (nuevoAño === añoMaximo && nuevoMes > mesMaximo)) {
        alert("Solo se pueden programar paseos hasta 3 días del próximo mes.");
        return;
    }
    mesActual = nuevoMes;
    añoActual = nuevoAño;
    cargarCalendario(mesActual, añoActual);
});

$("#btnSolicitar").click(function(){
    if (!fechaSeleccionada || !horaSeleccionada) {
        alert("Debes seleccionar una fecha y hora.");
        return;
    }

    var fechaHora = fechaSeleccionada + " " + horaSeleccionada + ":00";
    var fechaFin = new Date(fechaSeleccionada + "T" + horaSeleccionada + ":00");
    fechaFin.setHours(fechaFin.getHours() + 1);
    var fechaFinStr = fechaFin.getFullYear() + "-" +
        ("0" + (fechaFin.getMonth() + 1)).slice(-2) + "-" +
        ("0" + fechaFin.getDate()).slice(-2) + " " +
        ("0" + fechaFin.getHours()).slice(-2) + ":00:00";

    var datos = {
        idPerro1: perrosSeleccionados[0],
        idPerro2: perrosSeleccionados.length > 1 ? perrosSeleccionados[1] : 0,
        idPaseador: paseadorSeleccionado,
        fechaInicio: fechaHora,
        fechaFin: fechaFinStr,
        bozal: necesitaBozal ? 1 : 0
    };

    $("#btnSolicitar").prop("disabled", true).html('<span class="spinner-border spinner-border-sm"></span> Enviando...');

    $.ajax({
        url: "ajax/solicitarPaseoAjax.php",
        method: "POST",
        data: datos,
        dataType: "json",
        success: function(respuesta){
            if (respuesta.exito) {
                $("#mensajeContainer").html('<div class="alert alert-success">' + respuesta.mensaje + '</div>');
                setTimeout(function(){
                    window.location.href = "?pid=<?php echo base64_encode("presentacion/paseo/historialPaseosd.php"); ?>";
                }, 2000);
            } else {
                $("#mensajeContainer").html('<div class="alert alert-danger">' + respuesta.mensaje + '</div>');
                $("#btnSolicitar").prop("disabled", false).text("Solicitar Paseo");
            }
        },
        error: function(){
            $("#mensajeContainer").html('<div class="alert alert-danger">Error de conexión. Intente nuevamente.</div>');
            $("#btnSolicitar").prop("disabled", false).text("Solicitar Paseo");
        }
    });
});
</script>

</body>
</html>
