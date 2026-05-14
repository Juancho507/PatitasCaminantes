<?php

$exito = false;
$error = false;
$correoDuplicado = false;
$errorEnSubidaFoto = false;
$mensaje = "";
$claseMensaje = "";
$fotoRuta = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $correo = $_POST["correo"];
    $clave = $_POST["clave"];
    $contacto = $_POST["contacto"];
    $informacion = $_POST["informacion"];
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
        $paseador = new Paseador("", $nombre, $apellido, $correo, $clave, $contacto, $fotoRuta, $informacion);
        
        if ($paseador->correoExiste()) {
            $correoDuplicado = true;
        } else {
            try {
                $paseador->registrar();
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
?>

<body style="background-color:rgb(242, 231, 208); font-family: 'Segoe UI', sans-serif; min-height: 100vh; position: relative;">
  <div style="position: absolute; top: 10px; left: 100px;">
    <div class="rounded-circle overflow-hidden shadow" style="width: 110px; height: 110px;">
      <img src="img/logo.png" alt="Logo DoggyToons" style="width: 100%; height: 100%; object-fit: cover;">
    </div>
  </div>

  

  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 mt-3">
        <h2 class="text-center">Registrar nuevo paseador</h2>
      <form method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" autocomplete="off" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Apellido</label>
          <input type="text" name="apellido" class="form-control" autocomplete="off" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Correo</label>
          <input type="email" name="correo" class="form-control" autocomplete="off" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input type="password" name="clave" class="form-control" autocomplete="new-password" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Contacto</label>
          <input type="number" name="contacto" class="form-control" autocomplete="off" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Información</label>
          <input type="text" name="informacion" class="form-control" autocomplete="off" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Foto de perfil (solo PNG)</label>
          <input type="file" name="foto" class="form-control">
        </div>
        <?php if ($exito): ?>
          <div class="alert alert-success text-center mb-3">✅ ¡Usuario registrado exitosamente!</div>
        <?php elseif ($correoDuplicado || $error): ?>
          <div class="alert alert-danger text-center mb-3">
             <?php
              if ($correoDuplicado) {
                  echo "⚠️ El correo ya está registrado. Intenta con otro.";
              } else {
                  echo "❌ Error al registrar el usuario. Inténtalo de nuevo.";
              }
            ?>
          </div>
        <?php elseif ($errorEnSubidaFoto && !empty($mensaje)): ?>
          <div class="alert <?= $claseMensaje ?> text-center mb-3"><?= $mensaje ?></div>
        <?php endif; ?>

        <button type="submit" name="registrarPaseador" class="btn w-100" style="background-color:rgba(255, 115, 0, 0.84); color: white; border: none;">Registrar</button>
      </form>
    </div>
  </div>

  <div class="text-center mt-3 mb-5">
    <a href="?pid=<?php echo base64_encode('presentacion/sesionAdministrador.php'); ?>" class="text-decoration-none" style="color:#e06b17;">← Volver al inicio</a>
  </div>
</body>

