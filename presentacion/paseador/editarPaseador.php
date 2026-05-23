<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"]) && $_POST["ajax"] === "localidad") {
    while (ob_get_level()) ob_end_clean();
    $localidadObj = new Localidad();
    $lista = $localidadObj->consultarPorCiudad(intval($_POST["ciudad_id"]));
    $options = [];
    foreach ($lista as $l) {
        $options[] = ["id" => $l->getId(), "nombre" => $l->getNombre()];
    }
    header("Content-Type: application/json");
    echo json_encode($options);
    exit;
}

if ($_SESSION["rol"] != "paseador") {
    header("Location: ?pid=" . base64_encode("presentacion/noAutorizado.php"));
    exit();
}
$error = 0; 
$id = $_SESSION["id"];
$paseador = new Paseador($id);
$paseador->consultar(); 


if (isset($_POST["editar"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $claveNueva = $_POST["clave"];
    $contacto = isset($_POST["contacto"]) ? trim($_POST["contacto"]) : "0";
    if ($contacto === "" || !is_numeric($contacto)) {
        $contacto = "0"; 
    }
    $informacion = isset($_POST["informacion"]) ? $_POST["informacion"] : "";
    $localidad_id = intval($_POST["localidad"] ?? 0);
    $foto = $_FILES["foto"]["name"];
    $tam = $_FILES["foto"]["size"];
    $rutaLocal = $_FILES["foto"]["tmp_name"];
    
    $claveFinal = $paseador->getClave();
    if (!empty($claveNueva)) {
        $claveFinal = md5($claveNueva);
    }
    
    $rutaServidor = $paseador->getFoto();
    if ($foto != "") {
        $nuevoNombre = time() . ".png";
        $rutaServidor = "imagenes/" . $nuevoNombre;
        if (copy($rutaLocal, $rutaServidor)) {
            if ($paseador->getFoto() != "") {
                $rutaFoto = __DIR__ . "/../../" . $paseador->getFoto();
                if (file_exists($rutaFoto)) {
                    unlink($rutaFoto);
                }
            }
        } else {
            $error = 1;
        }
    }

    $certNombre = "";
    if (isset($_FILES["certificados"]) && $_FILES["certificados"]["error"] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES["certificados"]["name"], PATHINFO_EXTENSION));
        if ($ext === "pdf") {
            $certNombre = time() . "_cert_" . uniqid() . ".pdf";
            move_uploaded_file($_FILES["certificados"]["tmp_name"], "documentos/" . $certNombre);
        }
    }
    
    if ($error == 0) {
        $paseadorActualizado = new Paseador(
            $id,
            $nombre,
            $apellido,
            $correo,
            $claveFinal,
            $contacto,
            $rutaServidor,
            $informacion
            );
        $paseadorActualizado->setLocalidadId($localidad_id);
        if ($certNombre) $paseadorActualizado->setCertificados($certNombre);
        $paseadorActualizado->actualizar();
        $paseador = $paseadorActualizado; 
    }
}

$ciudad = new Ciudad();
$ciudades = $ciudad->consultarTodos();
?>
<body>
<?php
include("presentacion/encabezadoP.php");
include("presentacion/menuPaseador.php");
?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mb-4"> 
                <div class="card-header bg-primary text-white">
                    <h4>Editar Perfil</h4>
                </div>
                <div class="card-body">
                    <?php
                    if (isset($_POST["editar"]) && $error == 0) {
                        echo "<div class='alert alert-success'>Datos actualizados correctamente.</div>";
                    } elseif (isset($_POST["editar"]) && $error == 1) {
                        echo "<div class='alert alert-danger'>Error al actualizar la foto.</div>";
                    }
                    ?>
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($paseador->getNombre()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($paseador->getApellido()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo</label>
                            <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($paseador->getCorreo()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nueva Clave (dejar en blanco si no deseas cambiarla)</label>
                            <input type="password" name="clave" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contacto</label>
                            <input type="text" name="contacto" class="form-control" value="<?php echo htmlspecialchars($paseador->getContacto()); ?>" required>
                        </div>
                         <div class="mb-3">
                             <label class="form-label">Información / Descripción</label>
                             <textarea name="informacion" class="form-control" rows="3" required><?php echo htmlspecialchars($paseador->getInformacion()); ?></textarea>
                         </div>
                         <div class="mb-3">
                           <label class="form-label">Ciudad</label>
                           <select name="ciudad" id="ciudad" class="form-select">
                             <option value="">Seleccione una ciudad</option>
                             <?php foreach ($ciudades as $c): ?>
                               <option value="<?= $c->getId() ?>" <?= ($paseador->getCiudadNombre() === $c->getNombre()) ? 'selected' : '' ?>><?= htmlspecialchars($c->getNombre()) ?></option>
                             <?php endforeach; ?>
                           </select>
                         </div>
                         <div class="mb-3">
                           <label class="form-label">Barrio / Localidad</label>
                           <select name="localidad" id="localidad" class="form-select" data-selected="<?= $paseador->getLocalidadId() ?>">
                             <option value="">Primero seleccione una ciudad</option>
                             <?php if ($paseador->getLocalidadId()): ?>
                               <option value="<?= $paseador->getLocalidadId() ?>" selected><?= htmlspecialchars($paseador->getLocalidadNombre()) ?></option>
                             <?php endif; ?>
                           </select>
                         </div>
                         <?php if ($paseador->getCertificados()): ?>
                         <div class="mb-3">
                           <label class="form-label">Certificados actuales</label>
                           <div>
                             <a href="documentos/<?= htmlspecialchars($paseador->getCertificados()) ?>" target="_blank" class="btn btn-sm btn-outline-danger"><i class="fas fa-file-pdf"></i> Ver certificados</a>
                           </div>
                         </div>
                         <?php endif; ?>
                         <div class="mb-3">
                           <label class="form-label">Subir nuevos certificados (PDF opcional)</label>
                           <input type="file" name="certificados" class="form-control" accept=".pdf">
                         </div>
                         <div class="mb-3 text-center">
                            <?php
                            if ($paseador->getFoto() != "" && file_exists($paseador->getFoto())) {
                                echo "<img src='" . $paseador->getFoto() . "' height='150' class='rounded-circle mb-2' />";
                            } else {
                                echo "<p>No hay foto actual.</p>";
                            }
                            ?>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Nueva</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                        <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                    </form>

                    

                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
  if ($('#ciudad').val()) {
    $('#ciudad').trigger('change');
  }

  $('#ciudad').change(function() {
    var ciudadId = $(this).val();
    var $localidad = $('#localidad');
    if (ciudadId) {
      $.ajax({
        type: 'POST',
        url: window.location.href,
        data: { ajax: 'localidad', ciudad_id: ciudadId },
        dataType: 'json',
        success: function(data) {
          var selectedLoc = $localidad.data('selected');
          $localidad.empty();
          $localidad.append('<option value="">Seleccione un barrio/localidad</option>');
          $.each(data, function(i, item) {
            $localidad.append('<option value="' + item.id + '"' + (selectedLoc == item.id ? ' selected' : '') + '>' + item.nombre + '</option>');
          });
        }
      });
    } else {
      $localidad.empty().append('<option value="">Primero seleccione una ciudad</option>');
    }
  });
});
</script>
</body>

