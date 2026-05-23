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

$exito = false;
$error = false;
$correoDuplicado = false;
$errorEnSubidaFoto = false;
$mensaje = "";
$claseMensaje = "";
$fotoRuta = "";
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $correo = trim($_POST["correo"] ?? "");
    $clave = $_POST["clave"] ?? "";
    $contacto = trim($_POST["contacto"] ?? "");
    $nroDocumento = trim($_POST["nroDocumento"] ?? "");
    $direccion = trim($_POST["direccion"] ?? "");
    $ciudad_id = intval($_POST["ciudad"] ?? 0);
    $localidad_id = intval($_POST["localidad"] ?? 0);

    if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) $errores['nombre'] = "El nombre solo debe contener letras.";
    if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellido)) $errores['apellido'] = "El apellido solo debe contener letras.";
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores['correo'] = "Ingrese un correo válido (ej: usuario@dominio.com).";
    if (strlen($clave) < 8 || !preg_match('/[a-zA-Z]/', $clave) || !preg_match('/[0-9]/', $clave)) $errores['clave'] = "La contraseña debe tener mínimo 8 caracteres, con letras y números.";
    if (!preg_match('/^\d+$/', $contacto)) $errores['contacto'] = "El contacto solo debe contener números.";
    if (!preg_match('/^\d+$/', $nroDocumento)) $errores['nroDocumento'] = "El documento solo debe contener números.";
    if (empty($direccion)) $errores['direccion'] = "La dirección es obligatoria.";
    if ($ciudad_id <= 0) $errores['ciudad'] = "Seleccione una ciudad.";
    if ($localidad_id <= 0) $errores['localidad'] = "Seleccione un barrio/localidad.";

    if (empty($errores)) {
        if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
            $nombreFotoOriginal = $_FILES["foto"]["name"];
            $rutaTemporal = $_FILES["foto"]["tmp_name"];
            $extension = pathinfo($nombreFotoOriginal, PATHINFO_EXTENSION);
            if (strtolower($extension) != 'png') {
                $mensaje = "Formato de imagen no permitido. Solo se aceptan imágenes PNG.";
                $claseMensaje = "alert-danger";
                $errorEnSubidaFoto = true;
            } else {
                $nuevoNombreFoto = time() . ".png";
                $directorioDestino = "imagenes/";          
                $rutaServidor = $directorioDestino . $nuevoNombreFoto;
                
                if (move_uploaded_file($rutaTemporal, $rutaServidor)) {
                    $fotoRuta = $rutaServidor;
                } else {
                    $mensaje = "Error al mover el archivo de la foto al servidor.";
                    $claseMensaje = "alert-danger";
                    $errorEnSubidaFoto = true;
                }
            }
        } else if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] != UPLOAD_ERR_NO_FILE) {
            $mensaje = "Error en la subida de la foto (código: " . $_FILES["foto"]["error"] . ").";
            $claseMensaje = "alert-danger";
            $errorEnSubidaFoto = true;
        }
        if (!$errorEnSubidaFoto) {
            $dueño = new Dueño("", $nombre, $apellido, $correo, $clave, $contacto, $fotoRuta, $nroDocumento, $direccion, $localidad_id);
            
            if ($dueño->correoExiste()) {
                $correoDuplicado = true;
                $errores['correo'] = "El correo ya está registrado.";
            } else {
                try {
                    $dueño->registrar();
                    $exito = true;
                    $_POST = [];
                } catch (Exception $e) {
                    $error = true;
                    if ($fotoRuta != "" && file_exists($fotoRuta)) {
                        unlink($fotoRuta);
                    }
                }
            }
        }
    }
}
?>

<body style="background-color: #EEE4FA; font-family: 'Segoe UI', sans-serif; min-height: 100vh; position: relative;">
  <div style="position: absolute; top: 10px; left: 100px;">
    <div class="rounded-circle overflow-hidden shadow" style="width: 110px; height: 110px;">
      <img src="img/patitas.png" alt="Logo DoggyToons" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 mt-3">
      <h2 class="text-center">Registrar nuevo dueño</h2>
      <form method="POST" enctype="multipart/form-data" autocomplete="off" id="formDueño">
        <div class="mb-3">
          <label class="form-label">Número de Documento</label>
          <input type="text" name="nroDocumento" class="form-control <?= isset($errores['nroDocumento']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['nroDocumento'] ?? '') ?>">
          <div class="invalid-feedback"><?= $errores['nroDocumento'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
          <div class="invalid-feedback"><?= $errores['nombre'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Apellido</label>
          <input type="text" name="apellido" class="form-control <?= isset($errores['apellido']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
          <div class="invalid-feedback"><?= $errores['apellido'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Correo</label>
          <input type="email" name="correo" class="form-control <?= isset($errores['correo']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
          <div class="invalid-feedback"><?= $errores['correo'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="clave" class="form-control <?= isset($errores['clave']) ? 'is-invalid' : '' ?>" autocomplete="new-password" required>
          <div class="invalid-feedback"><?= $errores['clave'] ?? '' ?></div>
          <small class="text-muted">Mínimo 8 caracteres, debe incluir letras y números.</small>
        </div>

        <div class="mb-3">
          <label class="form-label">Contacto</label>
          <input type="text" name="contacto" class="form-control <?= isset($errores['contacto']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['contacto'] ?? '') ?>">
          <div class="invalid-feedback"><?= $errores['contacto'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Dirección</label>
          <input type="text" name="direccion" class="form-control <?= isset($errores['direccion']) ? 'is-invalid' : '' ?>" autocomplete="off" required value="<?= htmlspecialchars($_POST['direccion'] ?? '') ?>">
          <div class="invalid-feedback"><?= $errores['direccion'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Ciudad</label>
          <select name="ciudad" id="ciudad" class="form-select <?= isset($errores['ciudad']) ? 'is-invalid' : '' ?>" required>
            <option value="">Seleccione una ciudad</option>
            <?php
            $ciudad = new Ciudad();
            $ciudades = $ciudad->consultarTodos();
            foreach ($ciudades as $c): ?>
              <option value="<?= $c->getId() ?>" <?= (isset($_POST['ciudad']) && intval($_POST['ciudad']) === $c->getId()) ? 'selected' : '' ?>><?= htmlspecialchars($c->getNombre()) ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback"><?= $errores['ciudad'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Barrio / Localidad</label>
          <select name="localidad" id="localidad" class="form-select <?= isset($errores['localidad']) ? 'is-invalid' : '' ?>" required>
            <option value="">Primero seleccione una ciudad</option>
          </select>
          <div class="invalid-feedback"><?= $errores['localidad'] ?? '' ?></div>
        </div>

        <div class="mb-3">
          <label class="form-label">Foto de perfil (solo PNG)</label>
          <input type="file" name="foto" class="form-control">
        </div>
        <?php if ($exito): ?>
          <div class="alert alert-success text-center mb-3">¡Usuario registrado exitosamente!</div>
        <?php elseif ($correoDuplicado || $error || $errorEnSubidaFoto): ?>
          <div class="alert alert-danger text-center mb-3">
             <?php
              if ($correoDuplicado) {
                  echo "El correo ya está registrado. Intenta con otro.";
              } elseif ($errorEnSubidaFoto && !empty($mensaje)) {
                  echo htmlspecialchars($mensaje);
              } else {
                  echo "Error al registrar el usuario. Inténtalo de nuevo.";
              }
            ?>
          </div>
        <?php endif; ?>

        <button type="submit" name="registrarDueño" class="btn w-100" style="background-color: #7e57c2; color: white; border: none;">Registrar</button>
      </form>
    </div>
  </div>

  <div class="text-center mt-3 mb-5">
    <a href="?pid=<?php echo base64_encode('presentacion/autenticarse.php'); ?>" class="text-decoration-none" style="color:#7e57c2;">← Volver al inicio</a>
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

      function validarCampo(input, regex, mensaje) {
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

      $('input[name="nombre"]').on('input', function() { validarCampo($(this), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El nombre solo debe contener letras.'); });
      $('input[name="apellido"]').on('input', function() { validarCampo($(this), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El apellido solo debe contener letras.'); });
      $('input[name="nroDocumento"]').on('input', function() { validarCampo($(this), /^\d+$/, 'El documento solo debe contener números.'); });
      $('input[name="contacto"]').on('input', function() { validarCampo($(this), /^\d+$/, 'El contacto solo debe contener números.'); });
      $('input[name="correo"]').on('input', function() { validarEmail($(this)); });
      $('input[name="clave"]').on('input', function() { validarClave($(this)); });

      $('#formDueño').on('submit', function(e) {
        var ok = true;
        ok = validarCampo($('input[name="nombre"]'), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El nombre solo debe contener letras.') && ok;
        ok = validarCampo($('input[name="apellido"]'), /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, 'El apellido solo debe contener letras.') && ok;
        ok = validarCampo($('input[name="nroDocumento"]'), /^\d+$/, 'El documento solo debe contener números.') && ok;
        ok = validarCampo($('input[name="contacto"]'), /^\d+$/, 'El contacto solo debe contener números.') && ok;
        ok = validarEmail($('input[name="correo"]')) && ok;
        ok = validarClave($('input[name="clave"]')) && ok;

        if (!$(this).find('input[name="direccion"]').val().trim()) {
          $('input[name="direccion"]').addClass('is-invalid').next('.invalid-feedback').text('La dirección es obligatoria.');
          ok = false;
        }

        if (!$('#ciudad').val()) {
          $('#ciudad').addClass('is-invalid');
          ok = false;
        }
        if (!$('#localidad').val()) {
          $('#localidad').addClass('is-invalid');
          ok = false;
        }

        if (!ok) e.preventDefault();
      });
    });
  </script>
</body>
