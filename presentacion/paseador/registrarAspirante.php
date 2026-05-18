<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ajax"]) && $_POST["ajax"] === "localidad") {
    while (ob_get_level()) ob_end_clean();
    require_once(__DIR__ . "/../../logica/Localidad.php");
    $localidad = new Localidad();
    $lista = $localidad->consultarPorCiudad(intval($_POST["ciudad_id"]));
    $options = [];
    foreach ($lista as $l) {
        $options[] = ["id" => $l->getId(), "nombre" => $l->getNombre()];
    }
    header("Content-Type: application/json");
    echo json_encode($options);
    exit;
}

$exito = false;
$error = false;
$documentoDuplicado = false;
$correoDuplicado = false;
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $nroDocumento = trim($_POST["nroDocumento"] ?? "");
    $contacto = trim($_POST["contacto"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $clave = $_POST["clave"] ?? "";
    $ciudad_id = intval($_POST["ciudad"] ?? 0);
    $localidad_id = intval($_POST["localidad"] ?? 0);
    $fechaNacimiento = trim($_POST["fechaNacimiento"] ?? "");

    if (empty($nombre)) $errores[] = "El nombre es obligatorio.";
    if (empty($apellido)) $errores[] = "El apellido es obligatorio.";
    if (empty($nroDocumento)) $errores[] = "El número de documento es obligatorio.";
    if (empty($contacto)) $errores[] = "El teléfono es obligatorio.";
    if (empty($correo)) $errores[] = "El correo es obligatorio.";
    if (empty($clave)) $errores[] = "La contraseña es obligatoria.";
    if (empty($fechaNacimiento)) $errores[] = "La fecha de nacimiento es obligatoria.";
    if ($ciudad_id <= 0) $errores[] = "Seleccione una ciudad.";
    if ($localidad_id <= 0) $errores[] = "Seleccione un barrio/localidad.";

    if (!empty($fechaNacimiento)) {
        $fechaNacDT = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacDT)->y;
        if ($edad < 18) $errores[] = "Debes ser mayor de 18 años para registrarte como paseador.";
    }

    $fotoRuta = "";
    $hojaDeVidaRuta = "";
    $certificadosRuta = "";

    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        if ($ext !== "png") $errores[] = "La foto debe ser formato PNG.";
    } else {
        $errores[] = "La foto es obligatoria.";
    }

    if (isset($_FILES["hojaDeVida"]) && $_FILES["hojaDeVida"]["error"] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES["hojaDeVida"]["name"], PATHINFO_EXTENSION));
        if ($ext !== "pdf") $errores[] = "La hoja de vida debe ser formato PDF.";
    } else {
        $errores[] = "La hoja de vida es obligatoria.";
    }

    if (isset($_FILES["certificados"]) && $_FILES["certificados"]["error"] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES["certificados"]["name"], PATHINFO_EXTENSION));
        if ($ext !== "pdf") $errores[] = "Los certificados deben ser formato PDF.";
    }

    if (empty($errores)) {
        $conexion = new Conexion();
        $conexion->abrir();

        $conexion->ejecutar("SELECT idPaseador FROM paseador WHERE NroDocumento = '$nroDocumento'");
        if ($conexion->filas() > 0) $documentoDuplicado = true;

        if (!$documentoDuplicado) {
            $conexion->ejecutar("SELECT idPaseador FROM paseador WHERE Correo = '$correo'");
            if ($conexion->filas() > 0) $correoDuplicado = true;
        }

        $conexion->cerrar();

        if (!$documentoDuplicado && !$correoDuplicado) {
            if (!is_dir("documentos/")) mkdir("documentos/", 0777, true);

            $ts = time();
            $fotoRuta = "imagenes/" . $ts . "_" . uniqid() . ".png";
            move_uploaded_file($_FILES["foto"]["tmp_name"], $fotoRuta);

            $hojaDeVidaRuta = "documentos/" . $ts . "_hv_" . uniqid() . ".pdf";
            move_uploaded_file($_FILES["hojaDeVida"]["tmp_name"], $hojaDeVidaRuta);

            if (isset($_FILES["certificados"]) && $_FILES["certificados"]["error"] === UPLOAD_ERR_OK) {
                $certificadosRuta = "documentos/" . $ts . "_cert_" . uniqid() . ".pdf";
                move_uploaded_file($_FILES["certificados"]["tmp_name"], $certificadosRuta);
            }

            $conexion = new Conexion();
            $conexion->abrir();
            $claveMd5 = md5($clave);

            $sql = "INSERT INTO paseador (Nombre, Apellido, NroDocumento, FechaNacimiento, Correo, Clave, Contacto, Foto, Informacion, Activo, Admin_idAdmin, Localidad_idLocalidad, HojaDeVida, Certificados)
                    VALUES ('$nombre', '$apellido', '$nroDocumento', '$fechaNacimiento', '$correo', '$claveMd5', '$contacto', '$fotoRuta', '', 2, 1, $localidad_id, '$hojaDeVidaRuta', '$certificadosRuta')";

            try {
                $conexion->ejecutar($sql);
                $exito = true;
                $_POST = [];
            } catch (Exception $e) {
                $error = true;
                if (file_exists($fotoRuta)) unlink($fotoRuta);
                if (file_exists($hojaDeVidaRuta)) unlink($hojaDeVidaRuta);
                if ($certificadosRuta && file_exists($certificadosRuta)) unlink($certificadosRuta);
            }
            $conexion->cerrar();
        }
    } else {
        $error = true;
    }
}

$ciudad = new Ciudad();
$ciudades = $ciudad->consultarTodos();
?>
<body style="background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); font-family: 'Segoe UI', sans-serif; min-height: 100vh; position: relative;">
  <div style="position: absolute; top: 20px; left: 40px;">
    <div class="rounded-circle overflow-hidden shadow" style="width: 90px; height: 90px; background: white; display: flex; align-items: center; justify-content: center;">
      <img src="img/logo.png" alt="Logo" style="width: 80%; height: 80%; object-fit: cover;">
    </div>
  </div>

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg border-0 rounded-4" style="background: rgba(255,255,255,0.95);">
          <div class="card-body p-4">
            <h2 class="text-center mb-1" style="color: #333;">Registrarse como Paseador</h2>
            <p class="text-center text-muted mb-4">Complete el formulario para enviar su solicitud</p>

            <form method="POST" enctype="multipart/form-data" autocomplete="off" id="aspiranteForm">
              <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control" autocomplete="off" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control" autocomplete="off" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Número de Documento</label>
                <input type="text" name="nroDocumento" class="form-control" autocomplete="off" required value="<?= htmlspecialchars($_POST['nroDocumento'] ?? '') ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Teléfono / Contacto</label>
                <input type="number" name="contacto" class="form-control" autocomplete="off" required value="<?= htmlspecialchars($_POST['contacto'] ?? '') ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" autocomplete="off" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="clave" class="form-control" autocomplete="new-password" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fechaNacimiento" class="form-control" required value="<?= htmlspecialchars($_POST['fechaNacimiento'] ?? '') ?>">
                <small class="text-muted">Debes ser mayor de 18 años para registrarte.</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <select name="ciudad" id="ciudad" class="form-select" required>
                  <option value="">Seleccione una ciudad</option>
                  <?php foreach ($ciudades as $c): ?>
                    <option value="<?= $c->getId() ?>" <?= (isset($_POST['ciudad']) && intval($_POST['ciudad']) === $c->getId()) ? 'selected' : '' ?>><?= htmlspecialchars($c->getNombre()) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Barrio / Localidad</label>
                <select name="localidad" id="localidad" class="form-select" required>
                  <option value="">Primero seleccione una ciudad</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label">Foto de perfil (solo PNG)</label>
                <input type="file" name="foto" class="form-control" accept=".png" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Hoja de Vida (PDF obligatorio)</label>
                <input type="file" name="hojaDeVida" class="form-control" accept=".pdf" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Certificados (PDF opcional)</label>
                <input type="file" name="certificados" class="form-control" accept=".pdf">
              </div>

              <?php if ($exito): ?>
                <div class="alert alert-success text-center">¡Solicitud enviada exitosamente! Su cuenta está en espera. Recibirá una respuesta en un máximo de una semana.</div>
              <?php elseif ($documentoDuplicado): ?>
                <div class="alert alert-danger text-center">El número de documento ya está registrado.</div>
              <?php elseif ($correoDuplicado): ?>
                <div class="alert alert-danger text-center">El correo electrónico ya está registrado.</div>
              <?php elseif ($error && !empty($errores)): ?>
                <div class="alert alert-danger text-center"><?= implode("<br>", array_map("htmlspecialchars", $errores)) ?></div>
              <?php elseif ($error): ?>
                <div class="alert alert-danger text-center">Error al registrar. Inténtelo de nuevo.</div>
              <?php endif; ?>

              <button type="submit" name="registrarAspirante" class="btn w-100 py-2" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; font-weight: 600;">Enviar Solicitud</button>
            </form>
          </div>
        </div>

        <div class="text-center mt-3 mb-5">
          <a href="?pid=<?php echo base64_encode('presentacion/autenticarse.php'); ?>" class="text-decoration-none" style="color: #333; font-weight: 500;">← Volver al inicio</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
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
              $localidad.empty();
              $localidad.append('<option value="">Seleccione un barrio/localidad</option>');
              $.each(data, function(i, item) {
                $localidad.append('<option value="' + item.id + '">' + item.nombre + '</option>');
              });
            }
          });
        } else {
          $localidad.empty().append('<option value="">Primero seleccione una ciudad</option>');
        }
      });

      $('#aspiranteForm').submit(function(e) {
        var valid = true;
        var firstError = null;
        $(this).find('input[required], select[required]').each(function() {
          if (!$(this).val()) {
            $(this).addClass('is-invalid');
            if (!firstError) firstError = $(this);
            valid = false;
          } else {
            $(this).removeClass('is-invalid');
          }
        });
        if (!valid) {
          e.preventDefault();
          if (firstError) firstError.focus();
        }
      });
    });
  </script>
</body>
