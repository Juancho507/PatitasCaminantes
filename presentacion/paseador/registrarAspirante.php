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

    $erroresCampos = [];
    if (empty($nombre)) $erroresCampos['nombre'] = "El nombre es obligatorio.";
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) $erroresCampos['nombre'] = "El nombre solo debe contener letras.";
    if (empty($apellido)) $erroresCampos['apellido'] = "El apellido es obligatorio.";
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellido)) $erroresCampos['apellido'] = "El apellido solo debe contener letras.";
    if (empty($nroDocumento)) $erroresCampos['nroDocumento'] = "El número de documento es obligatorio.";
    elseif (!preg_match('/^\d+$/', $nroDocumento)) $erroresCampos['nroDocumento'] = "El documento solo debe contener números.";
    if (empty($contacto)) $erroresCampos['contacto'] = "El teléfono es obligatorio.";
    elseif (!preg_match('/^\d+$/', $contacto)) $erroresCampos['contacto'] = "El teléfono solo debe contener números.";
    if (empty($correo)) $erroresCampos['correo'] = "El correo es obligatorio.";
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $erroresCampos['correo'] = "Ingrese un correo válido (ej: usuario@dominio.com).";
    if (empty($clave)) $erroresCampos['clave'] = "La contraseña es obligatoria.";
    elseif (strlen($clave) < 8 || !preg_match('/[a-zA-Z]/', $clave) || !preg_match('/[0-9]/', $clave)) $erroresCampos['clave'] = "La contraseña debe tener mínimo 8 caracteres, con letras y números.";
    if (empty($fechaNacimiento)) $erroresCampos['fechaNacimiento'] = "La fecha de nacimiento es obligatoria.";
    elseif (!empty($fechaNacimiento)) {
        $fechaNacDT = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacDT)->y;
        if ($edad < 18) $erroresCampos['fechaNacimiento'] = "Debes ser mayor de 18 años para registrarte como paseador.";
    }
    if ($ciudad_id <= 0) $erroresCampos['ciudad'] = "Seleccione una ciudad.";
    if ($localidad_id <= 0) $erroresCampos['localidad'] = "Seleccione un barrio/localidad.";
    $errores = array_values($erroresCampos);

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

            $nombreHV = $ts . "_hv_" . uniqid() . ".pdf";
            $hojaDeVidaRuta = "documentos/" . $nombreHV;
            move_uploaded_file($_FILES["hojaDeVida"]["tmp_name"], $hojaDeVidaRuta);

            $nombreCert = "";
            $certificadosRuta = "";
            if (isset($_FILES["certificados"]) && $_FILES["certificados"]["error"] === UPLOAD_ERR_OK) {
                $nombreCert = $ts . "_cert_" . uniqid() . ".pdf";
                $certificadosRuta = "documentos/" . $nombreCert;
                move_uploaded_file($_FILES["certificados"]["tmp_name"], $certificadosRuta);
            }

            $conexion = new Conexion();
            $conexion->abrir();
            $claveMd5 = md5($clave);

            $sql = "INSERT INTO paseador (Nombre, Apellido, NroDocumento, FechaNacimiento, Correo, Clave, Contacto, Foto, Informacion, Estado_idEstado, Admin_idAdmin, Localidad_idLocalidad, HojaDeVida, Certificados)
                    VALUES ('$nombre', '$apellido', '$nroDocumento', '$fechaNacimiento', '$correo', '$claveMd5', '$contacto', '$fotoRuta', '', 1, 1, $localidad_id, '$nombreHV', '$nombreCert')";

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
      <img src="img/patitas.png" alt="Logo" style="width: 80%; height: 80%; object-fit: cover;">
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
                <input type="text" name="nombre" class="form-control <?= isset($erroresCampos['nombre']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                <div class="invalid-feedback"><?= $erroresCampos['nombre'] ?? '' ?></div>
              </div>

              <div class="mb-3">
                <label class="form-label">Apellido</label>
                <input type="text" name="apellido" class="form-control <?= isset($erroresCampos['apellido']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
                <div class="invalid-feedback"><?= $erroresCampos['apellido'] ?? '' ?></div>
              </div>

              <div class="mb-3">
                <label class="form-label">Número de Documento</label>
                <input type="text" name="nroDocumento" class="form-control <?= isset($erroresCampos['nroDocumento']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['nroDocumento'] ?? '') ?>">
                <div class="invalid-feedback"><?= $erroresCampos['nroDocumento'] ?? '' ?></div>
              </div>

              <div class="mb-3">
                <label class="form-label">Teléfono / Contacto</label>
                <input type="text" name="contacto" class="form-control <?= isset($erroresCampos['contacto']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['contacto'] ?? '') ?>">
                <div class="invalid-feedback"><?= $erroresCampos['contacto'] ?? '' ?></div>
              </div>

              <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control <?= isset($erroresCampos['correo']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
                <div class="invalid-feedback"><?= $erroresCampos['correo'] ?? '' ?></div>
              </div>

              <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="clave" class="form-control <?= isset($erroresCampos['clave']) ? 'is-invalid' : '' ?>" autocomplete="new-password" required>
                <div class="invalid-feedback"><?= $erroresCampos['clave'] ?? '' ?></div>
                <small class="text-muted">Mínimo 8 caracteres, debe incluir letras y números.</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Fecha de Nacimiento</label>
                <input type="date" name="fechaNacimiento" class="form-control <?= isset($erroresCampos['fechaNacimiento']) ? 'is-invalid' : '' ?>" required value="<?= htmlspecialchars($_POST['fechaNacimiento'] ?? '') ?>">
                <div class="invalid-feedback"><?= $erroresCampos['fechaNacimiento'] ?? '' ?></div>
                <small class="text-muted">Debes ser mayor de 18 años para registrarte.</small>
              </div>

              <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <select name="ciudad" id="ciudad" class="form-select <?= isset($erroresCampos['ciudad']) ? 'is-invalid' : '' ?>" required>
                  <option value="">Seleccione una ciudad</option>
                  <?php foreach ($ciudades as $c): ?>
                    <option value="<?= $c->getId() ?>" <?= (isset($_POST['ciudad']) && intval($_POST['ciudad']) === $c->getId()) ? 'selected' : '' ?>><?= htmlspecialchars($c->getNombre()) ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback"><?= $erroresCampos['ciudad'] ?? '' ?></div>
              </div>

              <div class="mb-3">
                <label class="form-label">Barrio / Localidad</label>
                <select name="localidad" id="localidad" class="form-select <?= isset($erroresCampos['localidad']) ? 'is-invalid' : '' ?>" required>
                  <option value="">Primero seleccione una ciudad</option>
                </select>
                <div class="invalid-feedback"><?= $erroresCampos['localidad'] ?? '' ?></div>
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
    function validarTexto(input, regex, mensaje) {
      var val = input.val().trim();
      if (val && !regex.test(val)) {
        input.addClass('is-invalid');
        input.next('.invalid-feedback').text(mensaje);
        return false;
      } else {
        input.removeClass('is-invalid');
        input.next('.invalid-feedback').text('');
        return true;
      }
    }

    function validarEmail(input) {
      var val = input.val().trim();
      if (val && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
        input.addClass('is-invalid');
        input.next('.invalid-feedback').text('Ingrese un correo válido (ej: usuario@dominio.com).');
        return false;
      } else {
        input.removeClass('is-invalid');
        input.next('.invalid-feedback').text('');
        return true;
      }
    }

    function validarClave(input) {
      var val = input.val();
      if (val && (val.length < 8 || !/[a-zA-Z]/.test(val) || !/[0-9]/.test(val))) {
        input.addClass('is-invalid');
        input.next('.invalid-feedback').text('La contraseña debe tener mínimo 8 caracteres, con letras y números.');
        return false;
      } else {
        input.removeClass('is-invalid');
        input.next('.invalid-feedback').text('');
        return true;
      }
    }

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

      $('input[name="nombre"]').on('input', function() { validarTexto($(this), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El nombre solo debe contener letras.'); });
      $('input[name="apellido"]').on('input', function() { validarTexto($(this), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El apellido solo debe contener letras.'); });
      $('input[name="nroDocumento"]').on('input', function() { validarTexto($(this), /^\d+$/, 'El documento solo debe contener números.'); });
      $('input[name="contacto"]').on('input', function() { validarTexto($(this), /^\d+$/, 'El teléfono solo debe contener números.'); });
      $('input[name="correo"]').on('input', function() { validarEmail($(this)); });
      $('input[name="clave"]').on('input', function() { validarClave($(this)); });

      $('input[name="fechaNacimiento"]').on('change', function() {
        var val = $(this).val();
        if (val) {
          var fecha = new Date(val);
          var hoy = new Date();
          var edad = hoy.getFullYear() - fecha.getFullYear();
          var m = hoy.getMonth() - fecha.getMonth();
          if (m < 0 || (m === 0 && hoy.getDate() < fecha.getDate())) edad--;
          if (edad < 18) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text('Debes ser mayor de 18 años para registrarte como paseador.');
          } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
          }
        }
      });

      $('#aspiranteForm').submit(function(e) {
        var valid = true;
        valid = validarTexto($('input[name="nombre"]'), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El nombre solo debe contener letras.') && valid;
        valid = validarTexto($('input[name="apellido"]'), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El apellido solo debe contener letras.') && valid;
        valid = validarTexto($('input[name="nroDocumento"]'), /^\d+$/, 'El documento solo debe contener números.') && valid;
        valid = validarTexto($('input[name="contacto"]'), /^\d+$/, 'El teléfono solo debe contener números.') && valid;
        valid = validarEmail($('input[name="correo"]')) && valid;
        valid = validarClave($('input[name="clave"]')) && valid;

        var fechaVal = $('input[name="fechaNacimiento"]').val();
        if (fechaVal) {
          var fecha = new Date(fechaVal);
          var hoy = new Date();
          var edad = hoy.getFullYear() - fecha.getFullYear();
          var m = hoy.getMonth() - fecha.getMonth();
          if (m < 0 || (m === 0 && hoy.getDate() < fecha.getDate())) edad--;
          if (edad < 18) {
            $('input[name="fechaNacimiento"]').addClass('is-invalid').next('.invalid-feedback').text('Debes ser mayor de 18 años para registrarte como paseador.');
            valid = false;
          }
        }

        if (!$('#ciudad').val()) {
          $('#ciudad').addClass('is-invalid');
          valid = false;
        }
        if (!$('#localidad').val()) {
          $('#localidad').addClass('is-invalid');
          valid = false;
        }

        if (!valid) e.preventDefault();
      });
    });
  </script>
</body>
