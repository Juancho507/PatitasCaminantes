<?php
$idDueñoLogueado = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>

<body>
<?php
include("presentacion/encabezadoD.php");
include("presentacion/menu" . ucfirst($rol) . ".php");

$mensaje = "";
$claseMensaje = "";
$razaLogica = new Raza();
$razas = $razaLogica->consultarTodos();

if (isset($_POST["crearPerro"])) {
    $nombrePerro = $_POST["nombrePerro"] ?? '';
    $razaId = $_POST["razaId"] ?? '';
    $raza = new Raza($razaId);
    $fotoRuta = "";
    $errorEnSubidaFoto = false;

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
    } elseif ($_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $mensaje = "Error en la subida de la foto (código: " . $_FILES["foto"]["error"] . ").";
        $claseMensaje = "alert-danger";
        $errorEnSubidaFoto = true;
    }

    $dueño = new Dueño($idDueñoLogueado);

    if (!$errorEnSubidaFoto) {
        $perro = new Perro(nombre: $nombrePerro, foto: $fotoRuta, raza: $raza, dueño: $dueño);
        $guardadoExitoso = $perro->insertar();

        if ($guardadoExitoso) {
            $mensaje = "¡Éxito! Perro '" . htmlspecialchars($nombrePerro) . "' registrado correctamente.";
            $claseMensaje = "alert-success";
            $_POST = array();
        } else {
            $mensaje = "Error al registrar el perro. Verifique los datos e intente nuevamente.";
            $claseMensaje = "alert-danger";
            if ($fotoRuta && file_exists($fotoRuta)) {
                unlink($fotoRuta);
            }
        }
    }
}
if (isset($_POST["solicitarRaza"])) {
    $nombreRaza = trim($_POST["nuevaRaza"] ?? "");

    if (!empty($nombreRaza)) {
        $nuevaRaza = new Raza(0, $nombreRaza);
        $exito = $nuevaRaza->insertarSinTamaño();
        if ($exito) {
            $mensaje = "Solicitud enviada. Espere entre 10 y 15 minutos mientras el administrador aprueba la raza.";
            $claseMensaje = "alert-info";
        } else {
            $mensaje = "No se pudo enviar la solicitud. Intente más tarde.";
            $claseMensaje = "alert-danger";
        }
    } else {
        $mensaje = "Debe ingresar un nombre de raza válido.";
        $claseMensaje = "alert-warning";
    }
}
?>

<div class="container">
    <div class="row mt-4">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header"><h4>Registrar Nuevo Perro</h4></div>
                <div class="card-body">
                    <?php if ($mensaje): ?>
                        <div class="alert <?php echo $claseMensaje; ?>" role="alert">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= "?pid=" . base64_encode("presentacion/perro/registrarPerro.php"); ?>" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-4">
                            <label for="nombrePerro">Nombre del Perro:</label>
                            <input type="text" class="form-control" id="nombrePerro" name="nombrePerro" required
                                value="<?php echo isset($_POST['nombrePerro']) ? htmlspecialchars($_POST['nombrePerro']) : ''; ?>">
                        </div>

                        <div class="form-group mb-4">
                            <label for="razaId">Raza:</label>
                            <select class="form-control" id="razaId" name="razaId" required>
                                <option value="">Seleccione una raza</option>
                                <?php foreach ($razas as $razaItem):
                                if ($razaItem->getTamaño() == 5) continue;
                                        ?>
   												 <option value="<?php echo $razaItem->getId(); ?>"
      							  <?php echo isset($_POST['razaId']) && $_POST['razaId'] == $razaItem->getId() ? 'selected' : ''; ?>>
       								 <?php echo htmlspecialchars($razaItem->getNombre()); ?>
   									 </option>
								<?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="foto">Foto de Perfil:</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/png">
                            <small class="form-text text-muted">Solo se permiten imágenes PNG.</small>
                        </div>

                        <button type="submit" name="crearPerro" class="btn btn-primary mt-4">Registrar Perro</button>
                    </form>

                    <hr class="my-4">

                    <form method="POST">
                        <div class="form-group mb-3">
                            <label for="nuevaRaza">¿No encuentra la raza?</label>
                            <input type="text" class="form-control" id="nuevaRaza" name="nuevaRaza" placeholder="Ingrese el nombre de la nueva raza">
                        </div>
                        <button type="submit" name="solicitarRaza" class="btn btn-primary">Solicitar nueva raza</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
